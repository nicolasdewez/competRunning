<?php

namespace App\Model;

class Search
{
    /** @var string */
    private $year;

    /** @var string */
    private $type;

    /** @var string */
    private $ligue;

    /** @var string */
    private $department;

    /** @var string */
    private $challenge;

    /** @var \DateTime */
    private $startDate;

    /** @var \DateTime */
    private $endDate;

    /** @var float */
    private $distanceMin;

    /** @var float */
    private $distanceMax;

    /**
     * @return string
     */
    public function getYear(): string
    {
        return $this->year;
    }

    /**
     * @param string $year
     *
     * @return Search
     */
    public function setYear(string $year): Search
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return Search
     */
    public function setType(string $type = null): Search
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getLigue(): ?string
    {
        return $this->ligue;
    }

    /**
     * @param string $ligue
     *
     * @return Search
     */
    public function setLigue(string $ligue = null): Search
    {
        $this->ligue = $ligue;

        return $this;
    }

    /**
     * @return string
     */
    public function getDepartment(): ?string
    {
        return $this->department;
    }

    /**
     * @param string $department
     *
     * @return Search
     */
    public function setDepartment(string $department = null): Search
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @return string
     */
    public function getChallenge(): ?string
    {
        return $this->challenge;
    }

    /**
     * @param string $challenge
     *
     * @return Search
     */
    public function setChallenge(string $challenge = null): Search
    {
        $this->challenge = $challenge;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $startDate
     *
     * @return Search
     */
    public function setStartDate(\DateTime $startDate = null): Search
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime $endDate
     *
     * @return Search
     */
    public function setEndDate(\DateTime $endDate = null): Search
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return float
     */
    public function getDistanceMin(): float
    {
        return $this->distanceMin;
    }

    /**
     * @param float $distanceMin
     *
     * @return Search
     */
    public function setDistanceMin(float $distanceMin): Search
    {
        $this->distanceMin = $distanceMin;

        return $this;
    }

    /**
     * @return float
     */
    public function getDistanceMax(): float
    {
        return $this->distanceMax;
    }

    /**
     * @param float $distanceMax
     *
     * @return Search
     */
    public function setDistanceMax(float $distanceMax): Search
    {
        $this->distanceMax = $distanceMax;

        return $this;
    }
}
