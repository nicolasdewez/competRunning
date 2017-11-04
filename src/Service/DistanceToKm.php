<?php

namespace App\Service;

class DistanceToKm
{
    const DISTANCE_REGEX = "#(?<distance>[0-9]+) m#";

    /**
     * @param string $distance
     *
     * @return float
     *
     * @throws \RuntimeException
     */
    public function execute(string $distance): float
    {
        $distance = trim($distance);

        // Default value for delete
        if ('' === $distance) {
            return -1;
        }

        preg_match(self::DISTANCE_REGEX, $distance, $matches);

        if (!isset($matches['distance'])) {
            throw new \RuntimeException('The format of distance is invalid');
        }

        return (float) ((float)$matches['distance'] / 1000);
    }
}
