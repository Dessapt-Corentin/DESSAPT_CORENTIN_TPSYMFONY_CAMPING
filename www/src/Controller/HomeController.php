<?php

namespace App\Controller;

use App\Repository\AccommodationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    /**
     * Méthode qui retourne la page d'accueil avec tous les jeux
     * @param AccommodationRepository $AccommodationRepository
     * @return Response
     */
    #[Route('/', name: 'app_home')]
    public function index(AccommodationRepository $accommodationRepository): Response
    {
        $title = 'Camping de la Plage';

        $accommodations = $accommodationRepository->findAll();

        return $this->render('home/index.html.twig', [
            'title' => $title,
            'accommodations' => $accommodations
        ]);
    }
}