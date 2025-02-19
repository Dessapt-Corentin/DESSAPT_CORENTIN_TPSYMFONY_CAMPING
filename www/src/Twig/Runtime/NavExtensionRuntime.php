<?php

namespace App\Twig\Runtime;

use App\Repository\AccommodationRepository;
use Twig\Extension\RuntimeExtensionInterface;

class NavExtensionRuntime implements RuntimeExtensionInterface
{
    //on va déclarer une variable en private pour stocker l'instance de gameRepository
    private $accommodationRepository;

    public function __construct(AccommodationRepository $accommodationRepository)
    {
        $this->accommodationRepository = $accommodationRepository;
    }

    public function menuItems()
    {
        return $this->accommodationRepository->getCountAccommodationByType();
    }
}
