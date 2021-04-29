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

        # Créer un nouveau flight pour alimenter la DB:
        $flight = new Flight;
        $flight
            ->setFlightNumber("PL03771")
            ->setFlightSchedule(\DateTime::createFromFormat("H:i", "08:00"))
            ->setFlightPrice(342.60)
            ->setDiscount(False)
            ->setDeparture($tabObjectsCities[1])
            ->setArrival($tabObjectsCities[0]);
        $manager->persist($flight);

        $manager->flush();

    }

}
