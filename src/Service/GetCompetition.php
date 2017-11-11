<?php

namespace App\Service;

use App\Model\Competition;
use App\Model\Search;
use App\Model\Trial;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class GetCompetition
{
    const DETAIL_JS_REGEX = "#javascript:bddThrowCompet\('([0-9]+)', 0\)#";
    const DETAIL_URL = 'http://bases.athle.com/asp.net/competitions.aspx?base=calendrier&id=%s';

    const CITY_DEPARTMENT_REGEX = "#(?<city>.*) \(.* / (?<department>[0-9]+)\)#";

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

    /** @var DistanceToKm */
    private $distanceToKm;

    /**
     * @param DistanceToKm $distanceToKm
     */
    public function __construct(DistanceToKm $distanceToKm)
    {
        $this->distanceToKm = $distanceToKm;
    }

    /**
     * @param Search $search
     * @param string $name
     * @param string $code
     *
     * @return Competition|null
     */
    public function execute(Search $search, string $name, string $code): ?Competition
    {
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

        $matches = [];
        $city = $crawler->filter('.titles span')->text();
        preg_match(self::CITY_DEPARTMENT_REGEX, $city, $matches);

        $competition = (new Competition())
            ->setName($name)
            ->setDate($crawler->filter('#bddDetails tr td:nth-child(1) span')->text())
            ->setDepartment($matches['department'])
            ->setCity($matches['city'])
            ->setOrganizer($this->getValueOrganizer($linesOrganizer, self::ORGANIZER_NAME))
            ->setWeb($this->getValueOrganizer($linesOrganizer, self::ORGANIZER_WEB))
            ->setMail($this->getValueOrganizer($linesOrganizer, self::ORGANIZER_MAIL))
            ->setAddress($this->getValueOrganizer($linesOrganizer, self::ORGANIZER_ADDRESS))
            ->setAddressZip($this->getValueOrganizer($linesOrganizer, self::ORGANIZER_ZIP))
            ->setAddressCity($this->getValueOrganizer($linesOrganizer, self::ORGANIZER_CITY))
            ->setPhone($this->getValueOrganizer($linesOrganizer, self::ORGANIZER_PHONE))
            ->setServices($this->getServicesOrganizer($linesOrganizer))
            ->setTrials($this->getTrials($crawler, $search))
        ;

        return $competition->getCountTrials() ? $competition : null;
    }


    /**
     * @param Crawler $lines
     * @param string $info
     *
     * @return string
     */
    private function getValueOrganizer(Crawler $lines, string $info): string
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
     * @param Search  $search
     *
     * @return array
     */
    private function getTrials(Crawler $crawler, Search $search): array
    {
        $blocks = $crawler->filter('#bddDetails table.linedRed');
        if (2 > $blocks->count()) {
            return [];
        }

        $trials = $blocks->eq(1)->filter('tr > td > table > tr')->each(function(Crawler $node) use ($search) {
            if (7 !== $node->children()->count()) {
                return null;
            }

            $distance = $this->distanceToKm->execute($node->filter('td:nth-child(5)')->text());
            if ($search->getDistanceMin() > $distance || $distance > $search->getDistanceMax()) {
                return null;
            }

            return (new Trial())
                ->setDate($node->filter('td:nth-child(2)')->text())
                ->setName($node->filter('td:nth-child(3)')->text())
                ->setDistance($distance)
            ;
        });

        $trials = array_filter($trials, function($trial) {
            return null !== $trial;
        });

        return array_values($trials);
    }
}
