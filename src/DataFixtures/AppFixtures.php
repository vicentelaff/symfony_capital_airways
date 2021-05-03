<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Flight;
use App\Entity\User;
use App\Services\FlightService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $flightService;
    private $passwordEncoder;

    /**
     * On injecte un service dans le constructeur
     * 
     */
    function __construct(FlightService $fs, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->flightService = $fs;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Permet d'alimenter la DB avec des enregistrements de villes et de vols.
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        # Tableau des villes (capitales des pays européens):
        $cities = ["Paris", "Londres", "Lisbonne", "Madrid", "Rome", "Berlin", "Vienne", "Bruxelles", "Copenhague", "Helsinki",
        "Athènes", "Amsterdam", "Oslo", "Varsovie", "Moscou", "Stockholm", "Kiev"];

        # Stocker tous les objets créés dans le tableau suivant, grâce au foreach:
        $tabObjectsCities = [];
        foreach($cities as $city){
            $c = new City;
            $c->setCityName($city);
            $manager->persist($c);
            $tabObjectsCities[] = $c;
        }

        # Créer 3 nouveaux flights pour alimenter la DB:
        for ($i=0; $i<3; $i++){
            $flight = new Flight;
            $flight
                ->setFlightNumber($this->flightService->getFlightNumber())
                ->setFlightSchedule(\DateTime::createFromFormat("H:i", "08:00"))
                ->setFlightPrice(mt_rand(100,300))
                ->setDiscount(false)
                ->setDeparture($tabObjectsCities[$i])
                ->setArrival($tabObjectsCities[$i+mt_rand(1,5)])
                ->setPlaces(mt_rand(2, 50));
            $manager->persist($flight);
        }

        /** -----------------------------------
         * 2 Users
         * ------------------------------------*/

         $admin = new User;
         $encryptedpwd = $this->passwordEncoder->encodePassword($admin, "secret1");
         $admin
            ->setEmail("admin@capital-airways.com")
            ->setFirstName("Hercules")
            ->setRoles(["ROLE_ADMIN"])
            ->setPassword($encryptedpwd);
        $manager->persist($admin);

        $user = new User;
        $encryptedpwd = $this->passwordEncoder->encodePassword($user, "secret2");
        $user
           ->setEmail("zeus@capital-airways.com")
           ->setFirstName("Zeus")
           ->setRoles(["ROLE_USER"])
           ->setPassword($encryptedpwd);
       $manager->persist($user);
        
            $manager->flush();

    }

}
