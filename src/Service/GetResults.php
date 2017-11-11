<?php

namespace App\Service;

use App\Model\Search;
use Goutte\Client;

class GetResults
{
    const URL = 'http://bases.athle.com/asp.net/liste.aspx?frmbase=calendrier&frmmode=1&frmespace=0&%s';

    const FORM_YEAR = 'frmsaison';
    const FORM_TYPE = 'frmtype1';
    const FORM_LIGUE = 'frmligue';
    const FORM_DEPARTMENT = 'frmdepartement';
    const FORM_CHALLENGE = 'frmepreuve';
    const FORM_DATE = 'frmdate_%s%d';
    const FORM_PAGE = 'frmposition';

    const PAGE_REGEX = "#.*([0-9]+)\/[0-9]+#";

    /**
     * @param Search $search
     * @param int    $page
     *
     * @return array
     */
    public function execute(Search $search, $page = 0): array
    {
        $client = new Client();

        $parameters = http_build_query([
            self::FORM_YEAR => $search->getYear(),
            self::FORM_TYPE => $search->getType(),
            self::FORM_LIGUE => $search->getLigue(),
            self::FORM_DEPARTMENT => $search->getDepartment(),
            self::FORM_CHALLENGE => $search->getChallenge(),
            sprintf(self::FORM_DATE, 'j', 1) => null !== $search->getStartDate() ? (int)$search->getStartDate()->format('d') : '',
            sprintf(self::FORM_DATE, 'm', 1) => null !== $search->getStartDate() ? (int)$search->getStartDate()->format('m') : '',
            sprintf(self::FORM_DATE, 'a', 1) => null !== $search->getStartDate() ? $search->getStartDate()->format('Y') : '',
            sprintf(self::FORM_DATE, 'j', 2) => null !== $search->getEndDate() ? (int)$search->getEndDate()->format('d') : '',
            sprintf(self::FORM_DATE, 'm', 2) => null !== $search->getEndDate() ? (int)$search->getEndDate()->format('m') : '',
            sprintf(self::FORM_DATE, 'a', 2) => null !== $search->getEndDate() ? $search->getEndDate()->format('Y') : '',
            self::FORM_PAGE => $page,
        ]);

        $crawler = $client->request('GET', sprintf(self::URL, $parameters));

        $data = $crawler->filter('#ctnCalendrier tr td:nth-child(7) a')->extract(['href', '_text']);

        $selectPage = $crawler->filter('.barInputs > select');
        if (0 === $selectPage->count()) {
            return $data;
        }

        $nextPages = $selectPage->filter('option:selected')->nextAll();
        if (0 === $nextPages->count()) {
            return $data;
        }

        $matches = [];
        preg_match(self::PAGE_REGEX, $nextPages->text(), $matches);

        $data = array_merge($data, $this->execute($search, $matches[1] - 1));

        return $data;
    }
}
