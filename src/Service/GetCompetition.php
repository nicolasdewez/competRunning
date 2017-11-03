<?php

namespace App\Service;

use App\Model\Competition;
use App\Model\Trial;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class GetCompetition
{
    const DETAIL_JS_REGEX = "#javascript:bddThrowCompet\('([0-9]+)', 0\)#";
    const DETAIL_URL = 'http://bases.athle.com/asp.net/competitions.aspx?base=calendrier&id=%s';

    const ORGANIZER_NAME = 'Organisateur';
    const ORGANIZER_WEB = 'Site Web';
    const ORGANIZER_MAIL = 'Mèl';
    const ORGANIZER_ADDRESS = 'Adresse';
    const ORGANIZER_ZIP = 'Code Postal';
    const ORGANIZER_CITY = 'Ville';
    const ORGANIZER_PHONE = 'Téléphone 1';
    const ORGANIZER_SERVICES = 'Services';

    const ORGANIZER = [
        self::ORGANIZER_NAME,
        self::ORGANIZER_WEB,
        self::ORGANIZER_MAIL,
        self::ORGANIZER_ZIP,
        self::ORGANIZER_CITY,
        self::ORGANIZER_PHONE,
        self::ORGANIZER_SERVICES,
    ];

    /**
     * @param string $name
     * @param string $code
     *
     * @return Competition|null
     */
    public function execute(string $name, string $code): ?Competition
    {
        dump($code);
        $client = new Client();

        $matches = [];
        preg_match(self::DETAIL_JS_REGEX, $code, $matches);
        $crawler = $client->request('GET', sprintf(self::DETAIL_URL, $matches[1]));

        $blocks = $crawler->filter('#bddDetails table.linedRed');
        if (0 === $blocks->count()) {
            return null;
        }

        $linesOrganizer = $blocks->eq(0)->filter('tr > td > table > tr')->reduce(function(Crawler $node) {
            return in_array($node->filter('td:nth-child(1)')->text(), self::ORGANIZER);
        });

        $competition = (new Competition())
            ->setName($name)
            ->setDate($crawler->filter('#bddDetails tr td:nth-child(1) span')->text())
            ->setCity($crawler->filter('.titles span')->text())
            ->setOrganizer($this->getValueOrganizer($linesOrganizer, self::ORGANIZER_NAME))
            ->setWeb($this->getValueOrganizer($linesOrganizer, self::ORGANIZER_WEB))
            ->setMail($this->getValueOrganizer($linesOrganizer, self::ORGANIZER_MAIL))
            ->setAddress($this->getValueOrganizer($linesOrganizer, self::ORGANIZER_ADDRESS))
            ->setAddressZip($this->getValueOrganizer($linesOrganizer, self::ORGANIZER_ZIP))
            ->setAddressCity($this->getValueOrganizer($linesOrganizer, self::ORGANIZER_CITY))
            ->setPhone($this->getValueOrganizer($linesOrganizer, self::ORGANIZER_PHONE))
            ->setServices($this->getServicesOrganizer($linesOrganizer))
            ->setTrials($this->getTrials($crawler))
        ;

        return $competition;
    }


    /**
     * @param Crawler $lines
     * @param string $info
     *
     * @return string
     */
    private function getValueOrganizer(Crawler $lines, $info): string
    {
        $values = $lines->each(function(Crawler $node) use ($info) {
            if ($info === $node->filter('td:nth-child(1)')->text()) {
                return $node->filter('td:nth-child(3)')->text();
            }

            return '';
        });

        $values = array_filter($values, function($value) {
            return '' !== $value;
        });

        return count($values) ? array_pop($values) : '';
    }

    /**
     * @param Crawler $lines
     *
     * @return array
     */
    private function getServicesOrganizer(Crawler $lines): array
    {
        $services = $lines->each(function(Crawler $node) {
            if (self::ORGANIZER_SERVICES === $node->filter('td:nth-child(1)')->text()) {
                return $node->filter('td:nth-child(3) img')->extract(['title']);
            }

            return [];
        });

        $services = array_filter($services, function($service) {
            return count($service);
        });

        return count($services) ? array_pop($services) : [];
    }

    /**
     * @param Crawler $crawler
     *
     * @return array
     */
    private function getTrials(Crawler $crawler): array
    {
        $blocks = $crawler->filter('#bddDetails table.linedRed');
        if (2 > $blocks->count()) {
            return [];
        }

        $trials = $blocks->eq(1)->filter('tr > td > table > tr')->each(function(Crawler $node, int $index) {
            if (7 !== $node->children()->count()) {
                return;
            }

            return (new Trial())
                ->setDate($node->filter('td:nth-child(2)')->text())
                ->setName($node->filter('td:nth-child(3) b')->text())
                ->setDistance($node->filter('td:nth-child(5)')->text())
            ;
        });

        $trials = array_filter($trials, function($trial) {
            return null !== $trial;
        });

        return array_values($trials);
    }
}
