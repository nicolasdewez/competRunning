<?php

namespace App\Service;

use App\Model\Competition;

class CountTrials
{
    /**
     * @param Competition[] $competitions
     */
    public function execute(array $competitions): int
    {
        $count = 0;

        foreach  ($competitions as $competition) {
            $count += $competition->getCountTrials();
        }

        return $count;
    }
}
