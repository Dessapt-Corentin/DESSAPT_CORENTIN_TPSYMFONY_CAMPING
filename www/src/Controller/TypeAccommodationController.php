<?php

namespace App\Controller;

use App\Entity\TypeAccommodation;
use App\Form\TypeAccommodationType;
use App\Repository\TypeAccommodationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class TypeAccommodationController extends AbstractController
{
    #[Route('/type/accommodation', name: 'app_type_accommodation_index', methods: ['GET'])]
    public function index(TypeAccommodationRepository $typeAccommodationRepository): Response
    {
        return $this->render('type_accommodation/index.html.twig', [
            'type_accommodations' => $typeAccommodationRepository->findAll(),
        ]);
    }

    #[Route('/type/accommodation/new', name: 'app_type_accommodation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typeAccommodation = new TypeAccommodation();
        $form = $this->createForm(TypeAccommodationType::class, $typeAccommodation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typeAccommodation);
            $entityManager->flush();

            return $this->redirectToRoute('app_type_accommodation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type_accommodation/new.html.twig', [
            'type_accommodation' => $typeAccommodation,
            'form' => $form,
        ]);
    }

    #[Route('/type/accommodation/{id}', name: 'app_type_accommodation_show', methods: ['GET'])]
    public function show(TypeAccommodation $typeAccommodation): Response
    {
        return $this->render('type_accommodation/show.html.twig', [
            'type_accommodation' => $typeAccommodation,
        ]);
    }

    #[Route('/type/accommodation/{id}/edit', name: 'app_type_accommodation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypeAccommodation $typeAccommodation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypeAccommodationType::class, $typeAccommodation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_type_accommodation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type_accommodation/edit.html.twig', [
            'type_accommodation' => $typeAccommodation,
            'form' => $form,
        ]);
    }

    #[Route('/type/accommodation/{id}', name: 'app_type_accommodation_delete', methods: ['POST'])]
    public function delete(Request $request, TypeAccommodation $typeAccommodation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $typeAccommodation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($typeAccommodation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_type_accommodation_index', [], Response::HTTP_SEE_OTHER);
    }
}
