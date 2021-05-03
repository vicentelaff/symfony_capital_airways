<?php 

namespace App\Services;

class FlightService {

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getFlightNumber(): string
    {
        // On initialise une variable qui contiendra les 2 lettres aléatoires:
        $rdChars = "";
        // Tableau de lettres de A-Z:
        $char = range("A", "Z");
        shuffle($char);
        // Extraire 2 fois la 1ère lettre du tableau:
        $rdChars = array_shift($char);
        $rdChars .= array_shift($char);

        // Pour le chifffre:
        $rdNumber = mt_rand(1000, 9999);
    
        return $rdChars.$rdNumber;
    }

}






?>