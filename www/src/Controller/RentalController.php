<?php

namespace App\Controller;

use App\Entity\Accommodation;
use App\Entity\Rental;
use App\Form\RentalType;
use App\Repository\RentalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\FormError;

#[Route('/rental')]
final class RentalController extends AbstractController
{
    #[Route(name: 'app_rental_index', methods: ['GET'])]
    public function index(RentalRepository $rentalRepository): Response
    {
        return $this->render('rental/index.html.twig', [
            'rentals' => $rentalRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_rental_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RentalRepository $rentalRepository, EntityManagerInterface $entityManager): Response
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
            'price' => $accommodation->getPricings()->first()->getPrice(),
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

    #[Route('/{id}', name: 'app_rental_show', methods: ['GET'])]
    public function show(Rental $rental): Response
    {
        return $this->render('rental/show.html.twig', [
            'rental' => $rental,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_rental_edit', methods: ['GET', 'POST'])]
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

    #[Route('/{id}', name: 'app_rental_delete', methods: ['POST'])]
    public function delete(Request $request, Rental $rental, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $rental->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($rental);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_rental_index', [], Response::HTTP_SEE_OTHER);
    }

    // Méthode pour confirmer une réservation avec son récapitulatif et possibilité d'annulation avant insertion en base
    #[Route('/confirm/{id}', name: 'app_rental_confirm', methods: ['GET', 'POST'])]
    public function confirm(Request $request, RentalRepository $rentalRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        $rental = $rentalRepository->find($id);
        if (!$rental) {
            throw $this->createNotFoundException('Rental not found.');
        }

        // Récupérer les infos de l'hébergement
        $accommodation = $rental->getAccommodation();

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
        ]);
    }
}
