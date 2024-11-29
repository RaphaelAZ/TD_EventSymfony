<?php

namespace App\Service;

class DistanceCalculator
{
    const EARTH_RADIUS_KM = 6371;

    public function calculateDistance(float $latitude1, float $longitude1, float $latitude2, float $longitude2): float
    {
        // Convert degrees to radians
        $latitude1 = deg2rad($latitude1);
        $longitude1 = deg2rad($longitude1);
        $latitude2 = deg2rad($latitude2);
        $longitude2 = deg2rad($longitude2);

        // Diff de lat et long
        $dlat = $latitude2 - $latitude1;
        $dlon = $longitude2 - $longitude1;

        // Méthode Haversine
        $a = sin($dlat / 2) * sin($dlat / 2) +
             cos($latitude1) * cos($latitude2) *
             sin($dlon / 2) * sin($dlon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // Distance en km
        return self::EARTH_RADIUS_KM * $c;
    }
}
