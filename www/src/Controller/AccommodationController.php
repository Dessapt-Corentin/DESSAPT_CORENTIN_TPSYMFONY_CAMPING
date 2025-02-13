<?php

namespace App\Controller;

use App\Entity\Accommodation;
use App\Form\AccommodationType;
use App\Repository\AccommodationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/accommodation')]
final class AccommodationController extends AbstractController
{
    #[Route(name: 'app_accommodation_index', methods: ['GET'])]
    public function index(AccommodationRepository $accommodationRepository): Response
    {
        return $this->render('accommodation/index.html.twig', [
            'accommodations' => $accommodationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_accommodation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $accommodation = new Accommodation();
        $form = $this->createForm(AccommodationType::class, $accommodation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '_' . uniqid() . '.' . $imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // handle exception if something happens during file upload
                }
                $accommodation->setImage($newFilename);
            }

            $entityManager->persist($accommodation);
            $entityManager->flush();

            return $this->redirectToRoute('app_accommodation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('accommodation/new.html.twig', [
            'accommodation' => $accommodation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_accommodation_show', methods: ['GET'])]
    public function show(Accommodation $accommodation): Response
    {
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
            'availability' => $accommodation->isAvailability(),
        ];

        return $this->render('accommodation/show.html.twig', [
            'accommodation' => $arrayAccommodation,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_accommodation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Accommodation $accommodation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AccommodationType::class, $accommodation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_accommodation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('accommodation/edit.html.twig', [
            'accommodation' => $accommodation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_accommodation_delete', methods: ['POST'])]
    public function delete(Request $request, Accommodation $accommodation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $accommodation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($accommodation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_accommodation_index', [], Response::HTTP_SEE_OTHER);
    }
}
