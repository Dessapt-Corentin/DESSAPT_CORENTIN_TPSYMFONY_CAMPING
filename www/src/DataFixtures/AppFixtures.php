<?php

namespace App\DataFixtures;

use App\Entity\Accommodation;
use App\Entity\Equipment;
use App\Entity\Pricing;
use App\Entity\Rental;
use App\Entity\Season;
use App\Entity\TypeAccommodation;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * Méthode pour charger les données de fixtures
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $this->loadUser($manager);
        $this->loadSeason($manager);
        $this->loadTypeAccommodation($manager);
        $this->loadEquipment($manager);
        $this->loadAccommodation($manager);
        $this->loadAccommodationEquipment($manager);
        $this->loadPricing($manager);
        $this->loadRental($manager);

        $manager->flush();
    }

    /**
     * Méthode pour créer un utilisateur
     * @param ObjectManager $manager
     */
    public function loadUser(ObjectManager $manager)
    {
        $array_user = [
            [
                'email' => 'admin@admin.com',
                'roles' => ['ROLE_ADMIN'],
                'password' => 'admin',
                'firstname' => 'Ad',
                'lastname' => 'min',
                'phone_number' => '0614256898',
                'address' => '1 rue du jaune',
            ],

            [
                'email' => 'user@user.com',
                'roles' => ['ROLE_USER'],
                'password' => 'user',
                'firstname' => 'Us',
                'lastname' => 'er',
                'phone_number' => '0614256898',
                'address' => '1 rue du vert',
            ]
        ];

        foreach ($array_user as $key => $userData) {
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setRoles($userData['roles']);
            $user->setPassword($this->encoder->hashPassword($user, $userData['password']));
            $user->setFirstname($userData['firstname']);
            $user->setLastname($userData['lastname']);
            $user->setPhoneNumber($userData['phone_number']);
            $user->setAddress($userData['address']);
            $manager->persist($user);
            $this->addReference('user_' . ($key + 1), $user);
        }
    }

    /**
     * Méthode pour créer type d'hébergement
     * @param ObjectManager $manager
     */
    public function loadTypeAccommodation(ObjectManager $manager)
    {
        $array_type_accommodation = [
            'Chalet',
            'Mobil-home',
            'Tente',
            'Yourte',
            'Tipi',
            'Camping-car',
            'Caravane',
            'Bungalow',
            'Emplacement vide'
        ];

        foreach ($array_type_accommodation as $key => $value) {
            $type_accommodation = new TypeAccommodation();
            $type_accommodation->setLabel($value);
            $manager->persist($type_accommodation);
            $this->addReference('type_' . ($key + 1), $type_accommodation);
        }
    }

    /**
     * Méthode pour créer les équipements
     * @param ObjectManager $manager
     */
    public function loadEquipment(ObjectManager $manager)
    {
        $array_equipment = [
            'Climatisation',
            'Chauffage',
            'Télévision',
            'Wifi',
            'Machine à laver',
            'Lave-vaisselle',
            'Micro-ondes',
            'Four',
            'Réfrigérateur',
            'Congélateur',
            'Cafetière',
            'Bouilloire',
            'Grille-pain',
            'Barbecue',
            'Salon de jardin',
            'Terrasse',
            'Balcon',
            'Piscine',
            'Spa',
            'Sauna',
            'Jacuzzi',
            'Hammam',
            'Salle de sport',
            'Parking',
            'Animaux acceptés',
            'Non-fumeur',
            'Accès handicapé',
            'Draps fournis',
            'Linge de toilette fourni'
        ];

        foreach ($array_equipment as $key => $equipmentLabel) {
            $equipment = new Equipment();
            $equipment->setLabel($equipmentLabel);
            $manager->persist($equipment);
            $this->addReference('equipment_' . ($key + 1), $equipment);
        }
    }

    /**
     * Méthode pour créer les équipements des hébergements
     * @param ObjectManager $manager
     */
    public function loadAccommodationEquipment(ObjectManager $manager)
    {
        $array_accommodation_equipment = [
            [
                'accommodation_id' => 1,
                'equipment_id' => 1,
            ],

            [
                'accommodation_id' => 1,
                'equipment_id' => 2,
            ],

            [
                'accommodation_id' => 1,
                'equipment_id' => 3,
            ],

            [
                'accommodation_id' => 1,
                'equipment_id' => 4,
            ],

            [
                'accommodation_id' => 2,
                'equipment_id' => 1,
            ],

            [
                'accommodation_id' => 2,
                'equipment_id' => 2,
            ],

            [
                'accommodation_id' => 2,
                'equipment_id' => 3,
            ],

            [
                'accommodation_id' => 2,
                'equipment_id' => 4,
            ]
        ];

        foreach ($array_accommodation_equipment as $accommodationEquipmentData) {
            $accommodation = $this->getReference('accommodation_' . $accommodationEquipmentData['accommodation_id']);
            $equipment = $this->getReference('equipment_' . $accommodationEquipmentData['equipment_id']);
            $accommodation->addEquipment($equipment);
            $manager->persist($accommodation);
        }
    }

    /**
     * Méthode pour créer les hébergements
     * @param ObjectManager $manager
     */
    public function loadAccommodation(ObjectManager $manager)
    {
        $array_accommodation = [
            [
                'label' => 'Jolie chalet',
                'location_number' => 'A1',
                'type_id' => 1,
                'size' => 30,
                'description' => 'Chalet 4 places',
                'capacity' => 4,
                'image' => 'chalet.jpg',
                'availability' => true,
            ],

            [
                'label' => 'Mobil-home',
                'location_number' => 'A2',
                'type_id' => 2,
                'size' => 25,
                'description' => 'Mobil-home 4 places',
                'capacity' => 4,
                'image' => 'mobil-home.jpg',
                'availability' => true,
            ]
        ];

        foreach ($array_accommodation as $key => $accommodationData) {
            $accommodation = new Accommodation();
            $accommodation->setLabel($accommodationData['label']);
            $accommodation->setLocationNumber($accommodationData['location_number']);
            $typeAccommodation = $this->getReference('type_' . $accommodationData['type_id']);
            $accommodation->setType($typeAccommodation);
            $accommodation->setSize($accommodationData['size']);
            $accommodation->setDescription($accommodationData['description']);
            $accommodation->setCapacity($accommodationData['capacity']);
            $accommodation->setImage($accommodationData['image']);
            $accommodation->setAvailability($accommodationData['availability']);
            $manager->persist($accommodation);
            $this->addReference('accommodation_' . ($key + 1), $accommodation);
        }
    }

    /**
     * Méthode pour créer les saisons
     * @param ObjectManager $manager
     */
    public function loadSeason(ObjectManager $manager)
    {
        $array_season = [
            [
                'label' => 'Basse saison',
                'date_start' => '01-01-2025',
                'date_end' => '30-06-2025',
            ],

            [
                'label' => 'Haute saison',
                'date_start' => '01-07-2025',
                'date_end' => '31-08-2025',
            ],

            [
                'label' => 'Fermeture annuelle',
                'date_start' => '01-10-2025',
                'date_end' => '31-03-2026',
            ]

        ];

        foreach ($array_season as $key => $seasonData) {
            $season = new Season();
            $season->setLabel($seasonData['label']);
            $season->setDateStart(new \DateTime($seasonData['date_start']));
            $season->setDateEnd(new \DateTime($seasonData['date_end']));
            $manager->persist($season);
            $this->addReference('season_' . ($key + 1), $season);
        }
    }

    /**
     * Méthode pour créer les tarifs
     * @param ObjectManager $manager
     */
    public function loadPricing(ObjectManager $manager)
    {
        $array_pricing = [
            [
                'price' => 50,
                'season_id' => 1,
                'accommodation_id' => 1,
            ],

            [
                'price' => 75,
                'season_id' => 2,
                'accommodation_id' => 1,
            ],

            [
                'price' => 0,
                'season_id' => 3,
                'accommodation_id' => 1,
            ],

            [
                'price' => 50,
                'season_id' => 1,
                'accommodation_id' => 2,
            ],

            [
                'price' => 75,
                'season_id' => 2,
                'accommodation_id' => 2,
            ],

            [
                'price' => 0,
                'season_id' => 3,
                'accommodation_id' => 2,
            ]
        ];

        foreach ($array_pricing as $pricingData) {
            $pricing = new Pricing();
            $season = $this->getReference('season_' . $pricingData['season_id']);
            $pricing->setSeason($season);
            $accommodation = $this->getReference('accommodation_' . $pricingData['accommodation_id']);
            $pricing->setAccommodation($accommodation);
            $pricing->setPrice($pricingData['price']);
            $manager->persist($pricing);
        }
    }

    /**
     * Méthode pour créer les locations
     * @param ObjectManager $manager
     */
    public function loadRental(ObjectManager $manager)
    {
        $array_rental = [
            [
                'user_id' => 1,
                'accommodation_id' => '1',
                'adult_number' => 2,
                'child_number' => 2,
                'date_start' => '01-07-2025',
                'date_end' => '15-07-2025',
            ]
        ];

        foreach ($array_rental as $rentalData) {
            $rental = new Rental();
            $user = $this->getReference('user_' . $rentalData['user_id']);
            $rental->setUser($user);
            $accommodation = $this->getReference('accommodation_' . $rentalData['accommodation_id']);
            $rental->setAccommodation($accommodation);
            $rental->setAdultNumber($rentalData['adult_number']);
            $rental->setChildNumber($rentalData['child_number']);
            $rental->setDateStart(new \DateTime($rentalData['date_start']));
            $rental->setDateEnd(new \DateTime($rentalData['date_end']));
            $manager->persist($rental);
        }
    }
}
