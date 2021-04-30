<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Flight;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
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
            $random_nb = strval(mt_rand(1000,9999));
            $flight
                ->setFlightNumber("CA$random_nb")
                ->setFlightSchedule(\DateTime::createFromFormat("H:i", "08:00"))
                ->setFlightPrice(mt_rand(100,300))
                ->setDiscount(false)
                ->setDeparture($tabObjectsCities[$i])
                ->setArrival($tabObjectsCities[$i+mt_rand(1,5)])
                ->setPlaces(mt_rand(2, 50));
            $manager->persist($flight);
        }
        
            $manager->flush();

    }

}
