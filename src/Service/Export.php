<?php

namespace App\Service;

use App\Model\Competition;

class Export
{
    const BUILD_FILE = 'competitions_%s.csv';

    /**
     * @param Competition[] $competitions
     */
    public function execute(array $competitions)
    {
        $handle = fopen($this->getCompletePath(), 'w');
        fputcsv($handle,
            [
                'Date',
                'Nom',
                'Ville',
                'Distance',
                'Libellé',
                'Départ',
                'Organisateur',
                'Site web',
                'Email',
                'Téléphone',
                'Adresse',
                'Services',
            ],
            ';'
        );

        foreach ($competitions as $competition) {
            foreach ($competition->getTrials() as $trial) {
                fputcsv(
                    $handle,
                    [
                        $competition->getDate(),
                        $competition->getName(),
                        $competition->getCity(),
                        $trial->getDistance(),
                        $trial->getName(),
                        $trial->getDate(),
                        $competition->getOrganizer(),
                        $competition->getWeb(),
                        $competition->getMail(),
                        $competition->getPhone(),
                        sprintf('%s %s %s', $competition->getAddress(), $competition->getAddressZip(), $competition->getAddressCity()),
                        implode(',', $competition->getServices()),
                    ],
                    ';'
                );
            }
        }

        fclose($handle);
    }

    /**
     * @return string
     */
    private function getCompletePath(): string
    {
        return sprintf(
            '%s/%s',
            $this->getBuildPath(),
            sprintf(
                self::BUILD_FILE,
                date('Ymd_His')
            )
        );
    }

    /**
     * @return string
     */
    private function getBuildPath(): string
    {
        return sprintf('%s/build', dirname(dirname(__DIR__)));
    }
}
