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

        // Récupérer les données de l'accommodation
        $accommodationsData = $accommodationRepository->getAccommodationsForIndex();

        // Supprimer les doublons d'accommodations
        $accommodations = [];
        $uniqueIds = [];

        foreach ($accommodationsData as $accommodation) {
            if (!in_array($accommodation['id'], $uniqueIds)) {
                $uniqueIds[] = $accommodation['id'];
                $accommodations[] = $accommodation;
            }
        }

        // Récupérer les prix pour chaque accommodation
        foreach ($accommodations as &$accommodation) {
            $accommodation['prices'] = $accommodationRepository->getPricesForAccommodation($accommodation['id']);
        }

        return $this->render('home/index.html.twig', [
            'title' => $title,
            'accommodations' => $accommodations
        ]);
    }

    /**
     * Méthode qui retourne le détail d'une accommodation avec toutes ses informations
     * @Route("/detail/{id}", name="app_detail")
     * @param AccommodationRepository $accommodationRepository
     * @param int $id
     * @return Response
     */
    #[Route('/detail/{id}', name: 'app_detail')]
    public function detail(AccommodationRepository $accommodationRepository, int $id): Response
    {
        $title = 'Détail de l\'hébergement';

        // Récupérer les données de l'accommodation
        $accommodationData = $accommodationRepository->getAccommodationWithInfo($id);

        if (empty($accommodationData)) {
            throw $this->createNotFoundException('Hébergement non trouvé.');
        }

        // Extraire les équipements et nettoyer les accommodations
        $equipments = array_column($accommodationData, 'equipments');

        // Supprimer les doublons d'accommodations
        $accommodation = array_unique($accommodationData, SORT_REGULAR);

        // Extraire le seul hébergement restant
        $accommodation = array_values($accommodation)[0];

        // Ajouter les équipements regroupés
        $accommodation['equipments'] = $equipments;

        // Récupérer les prix pour l'accommodation
        $accommodation['prices'] = $accommodationRepository->getPricesForAccommodation($accommodation['id']);

        return $this->render('home/detail.html.twig', [
            'title' => $title,
            'accommodation' => $accommodation
        ]);
    }

    /**
     * Méthode qui retourne la liste des accommodations par type
     * @Route("/type/{id}", name="app_console")
     * @param AccommodationRepository $accommodationRepository
     * @param int $id
     * @return Response
     */
    #[Route('/type/{id}', name: 'app_type')]
    public function accommodationsByType(AccommodationRepository $accommodationRepository, int $id): Response
    {
        // On récupère les accommodations filtrées par type
        $accommodations = $accommodationRepository->getAccommodationsByType($id);

        // On définit le titre avec le nom du type
        $title = 'Les ' .
            $accommodationRepository->getTypeLabelById($id) . 's' . ':';

        // Récupérer les prix pour chaque accommodation
        foreach ($accommodations as &$accommodation) {
            $accommodation['prices'] = $accommodationRepository->getPricesForAccommodation($accommodation['id']);
        }

        return $this->render('home/index.html.twig', [
            'accommodations' => $accommodations,
            'title' => $title
        ]);
    }
}
