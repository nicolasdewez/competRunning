<?php

namespace App\Model;

class Trial
{
    /** @var string */
    private $date;

    /** @var string */
    private $name;

    /** @var string */
    private $distance;

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @param string $date
     *
     * @return Trial
     */
    public function setDate(string $date): Trial
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Trial
     */
    public function setName(string $name): Trial
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDistance(): string
    {
        return $this->distance;
    }

    /**
     * @param string $distance
     *
     * @return Trial
     */
    public function setDistance(string $distance): Trial
    {
        $this->distance = $distance;

        return $this;
    }
}
