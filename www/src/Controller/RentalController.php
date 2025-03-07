<?php

namespace App\Controller;

use App\Entity\Rental;
use App\Form\RentalType;
use App\Entity\Accommodation;
use App\Repository\AccommodationRepository;
use App\Repository\PricingRepository;
use App\Repository\RentalRepository;
use App\Repository\SeasonRepository;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class RentalController extends AbstractController
{
    #[Route('admin/rental', name: 'app_rental_index', methods: ['GET'])]
    public function index(RentalRepository $rentalRepository): Response
    {
        return $this->render('rental/index.html.twig', [
            'rentals' => $rentalRepository->findAll(),
        ]);
    }
    #[Route('/rental/new', name: 'app_rental_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RentalRepository $rentalRepository, EntityManagerInterface $entityManager, AccommodationRepository $accommodationRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to create a rental.');
        }

        $accommodationId = $request->query->get('id');
        $accommodation = $entityManager->getRepository(Accommodation::class)->find($accommodationId);

        if (!$accommodation) {
            throw $this->createNotFoundException('Accommodation not found.');
        }

        $rental = new Rental();
        $rental->setAccommodation($accommodation);
        $rental->setUser($user);
        $form = $this->createForm(RentalType::class, $rental);
        $form->handleRequest($request);

        // Vérifier si la capacité de l'hébergement est suffisante en comptant et comparant avec adult_number + child_number
        $adultNumber = $form->get('adult_number')->getData();
        $childNumber = $form->get('child_number')->getData();
        $totalGuests = $adultNumber + $childNumber;
        if ($totalGuests > $accommodation->getCapacity()) {
            $form->addError(new FormError('La capacité de l\'hébergement est insuffisante pour le nombre de personnes indiqué.'));
        }

        // Vérifier si les dates sont dans le passé
        $currentDate = new \DateTime();
        if ($rental->getDateStart() < $currentDate || $rental->getDateEnd() < $currentDate) {
            $form->addError(new FormError('Vous ne pouvez pas réserver pour une date passée.'));
        }

        // Vérifier si les dates sont dans la période bloquée
        $blockedStartDate = new \DateTime('2025-10-01 00:00:00');
        $blockedEndDate = new \DateTime('2026-03-31 00:00:00');
        if (($rental->getDateStart() >= $blockedStartDate && $rental->getDateStart() <= $blockedEndDate) ||
            ($rental->getDateEnd() >= $blockedStartDate && $rental->getDateEnd() <= $blockedEndDate)
        ) {
            $form->addError(new FormError('Vous ne pouvez pas réserver pendant la période de fermeture.'));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $existingReservations = $rentalRepository->createQueryBuilder('r')
                ->where('r.accommodation = :accommodation')
                ->andWhere('r.date_start < :date_end')
                ->andWhere('r.date_end > :date_start')
                ->setParameter('accommodation', $accommodation)
                ->setParameter('date_start', $rental->getDateStart())
                ->setParameter('date_end', $rental->getDateEnd())
                ->getQuery()
                ->getResult();

            if (count($existingReservations) > 0) {
                $form->addError(new FormError('Cette période est déjà réservée. Veuillez choisir une autre date.'));
            } else {
                $entityManager->persist($rental);
                $entityManager->flush();
                return $this->redirectToRoute('app_rental_confirm', ['id' => $rental->getId()], Response::HTTP_SEE_OTHER);
            }
        }

        //Transformer l'objet accommodation en un tableau associatif pour l'afficher dans le formulaire
        $arrayAccommodation = [
            'id' => $accommodation->getId(),
            'label' => $accommodation->getLabel(),
            'location_number' => $accommodation->getLocationNumber(),
            'type' => $accommodation->getType()->getLabel(),
            'size' => $accommodation->getSize(),
            'prices' => $accommodationRepository->getPricesForAccommodation($accommodation->getId()),
            'description' => $accommodation->getDescription(),
            'equipments' => array_map(fn($equipment) => $equipment->getLabel(), $accommodation->getEquipments()->toArray()),
            'capacity' => $accommodation->getCapacity(),
            'image' => $accommodation->getImage(),
        ];

        return $this->render('rental/new.html.twig', [
            'rental' => $rental,
            'form' => $form,
            'accommodation' => $arrayAccommodation,
        ]);
    }

    #[Route('/admin/rental/new', name: 'app_admin_rental_new', methods: ['GET', 'POST'])]
    public function newAdmin(Request $request, RentalRepository $rentalRepository, EntityManagerInterface $entityManager): Response
    {
        $rental = new Rental();
        $form = $this->createForm(RentalType::class, $rental);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // L'utilisateur et l'hébergement sont déjà définis par le formulaire
            $user = $rental->getUser();
            $accommodation = $rental->getAccommodation();

            if (!$user || !$accommodation) {
                throw $this->createNotFoundException('User or Accommodation not found.');
            }

            // Vérifier la capacité
            $totalGuests = $rental->getAdultNumber() + $rental->getChildNumber();
            if ($totalGuests > $accommodation->getCapacity()) {
                $form->addError(new FormError('La capacité de l\'hébergement est insuffisante pour le nombre de personnes indiqué.'));
            } else {
                // Vérifier les réservations existantes
                $existingReservations = $rentalRepository->createQueryBuilder('r')
                    ->where('r.accommodation = :accommodation')
                    ->andWhere('r.date_start < :date_end')
                    ->andWhere('r.date_end > :date_start')
                    ->setParameter('accommodation', $accommodation)
                    ->setParameter('date_start', $rental->getDateStart())
                    ->setParameter('date_end', $rental->getDateEnd())
                    ->getQuery()
                    ->getResult();

                if (count($existingReservations) > 0) {
                    $form->addError(new FormError('Cette période est déjà réservée. Veuillez choisir une autre date.'));
                } else {
                    $entityManager->persist($rental);
                    $entityManager->flush();
                    return $this->redirectToRoute('app_rental_confirm', ['id' => $rental->getId()], Response::HTTP_SEE_OTHER);
                }
            }
        }

        // dd($form); // Remove or comment out this line to prevent halting the script

        return $this->render('/rental/new_admin.html.twig', [
            'rental' => $rental,
            'form' => $form,
        ]);
    }

    #[Route('/admin/rental/{id}', name: 'app_rental_show', methods: ['GET'])]
    public function show(Rental $rental): Response
    {
        return $this->render('rental/show.html.twig', [
            'rental' => $rental,
        ]);
    }

    #[Route('/admin/rental/{id}/edit', name: 'app_rental_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Rental $rental, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RentalType::class, $rental);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_rental_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rental/edit.html.twig', [
            'rental' => $rental,
            'form' => $form,
        ]);
    }

    #[Route('/admin/rental/{id}', name: 'app_rental_delete', methods: ['POST'])]
    public function delete(Request $request, Rental $rental, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $rental->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($rental);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_rental_index', [], Response::HTTP_SEE_OTHER);
    }
    // Méthode pour confirmer une réservation avec son récapitulatif et possibilité d'annulation avant insertion en base
    #[Route('/rental/confirm/{id}', name: 'app_rental_confirm', methods: ['GET', 'POST'])]
    public function confirm(Request $request, RentalRepository $rentalRepository, EntityManagerInterface $entityManager, SeasonRepository $seasonRepository, PricingRepository $pricingRepository, int $id): Response
    {
        $rental = $rentalRepository->find($id);
        if (!$rental) {
            throw $this->createNotFoundException('Rental not found.');
        }

        // Récupérer les infos de l'hébergement
        $accommodation = $rental->getAccommodation();

        // Récupérer la saison active
        $saisonActuelle = $seasonRepository->findSeasonsActiveOnCurrentDate();

        if (!$saisonActuelle) {
            // Si aucune saison active n'est trouvée, définir une saison par défaut
            $saisonActuelle = $seasonRepository->findOneBy(['label' => 'Haute saison']);
        }

        // Trouver le tarif pour le logement et la saison actuelle
        $tarif = $pricingRepository->findTarif($accommodation, $saisonActuelle);
        $pricePerNight = $tarif ? $tarif->getPrice() : 0;

        // Initialisation des variables
        $daysCount = 0;
        $totalPrice = 0;

        // Calcul automatique du nombre de jours avant soumission
        $dateStart = $rental->getDateStart();
        $dateEnd = $rental->getDateEnd();

        if ($dateStart && $dateEnd && $dateEnd > $dateStart) {
            $interval = $dateStart->diff($dateEnd);
            $daysCount = $interval->days;

            // Récupérer la saison correspondant aux dates de réservation
            $seasons = $seasonRepository->findSeasonsBetweenDates($dateStart, $dateEnd);
            if (!$seasons) {
                throw $this->createNotFoundException('No seasons found for the given dates.');
            }

            // Calculer le prix total en fonction des saisons
            foreach ($seasons as $season) {
                $seasonStart = $season->getDateStart();
                $seasonEnd = $season->getDateEnd();
                $seasonDays = min($dateEnd, $seasonEnd)->diff(max($dateStart, $seasonStart))->days + 1;

                $tarif = $pricingRepository->findTarif($accommodation, $season);
                $pricePerNight = $tarif ? $tarif->getPrice() : 0;

                $totalPrice += $seasonDays * $pricePerNight;
            }
        }

        if ($request->isMethod('POST')) {
            if ($request->request->get('action') === 'cancel') {
                $entityManager->remove($rental);
                $entityManager->flush();
            }
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rental/confirm.html.twig', [
            'rental' => $rental,
            'accommodation' => $accommodation,
            'daysCount' => $daysCount,
            'totalPrice' => $totalPrice,
        ]);
    }

    // Méthode pour afficher les réservations d'un utilisateur
    #[Route('/rental/user', name: 'app_rental_user', methods: ['GET'])]
    public function userRental(RentalRepository $rentalRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to view your rentals.');
        }

        $rentals = $rentalRepository->findRentalsByUser($user);

        return $this->render('rental/user.html.twig', [
            'rentals' => $rentals,
        ]);
    }
}
