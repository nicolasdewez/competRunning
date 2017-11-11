<?php

namespace App\Model;

class Competition
{
    /** @var string */
    private $name;

    /** @var string */
    private $date;

    /** @var string */
    private $city;

    /** @var string */
    private $department;

    /** @var string */
    private $organizer;

    /** @var string */
    private $web;

    /** @var string */
    private $mail;

    /** @var string */
    private $address;

    /** @var string */
    private $addressZip;

    /** @var string */
    private $addressCity;

    /** @var string */
    private $phone;

    /** @var array */
    private $services;

    /** @var Trial[] */
    private $trials;

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
     * @return Competition
     */
    public function setName(string $name): Competition
    {
        $this->name = $name;

        return $this;
    }

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
     * @return Competition
     */
    public function setDate(string $date): Competition
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     *
     * @return Competition
     */
    public function setCity(string $city): Competition
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string
     */
    public function getDepartment(): string
    {
        return $this->department;
    }

    /**
     * @param string $department
     *
     * @return Competition
     */
    public function setDepartment(string $department): Competition
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrganizer(): string
    {
        return $this->organizer;
    }

    /**
     * @param string $organizer
     *
     * @return Competition
     */
    public function setOrganizer(string $organizer): Competition
    {
        $this->organizer = $organizer;

        return $this;
    }

    /**
     * @return string
     */
    public function getWeb(): string
    {
        return $this->web;
    }

    /**
     * @param string $web
     *
     * @return Competition
     */
    public function setWeb(string $web): Competition
    {
        $this->web = $web;

        return $this;
    }

    /**
     * @return string
     */
    public function getMail(): string
    {
        return $this->mail;
    }

    /**
     * @param string $mail
     *
     * @return Competition
     */
    public function setMail(string $mail): Competition
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     *
     * @return Competition
     */
    public function setAddress(string $address): Competition
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddressZip(): string
    {
        return $this->addressZip;
    }

    /**
     * @param string $addressZip
     *
     * @return Competition
     */
    public function setAddressZip(string $addressZip): Competition
    {
        $this->addressZip = $addressZip;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddressCity(): string
    {
        return $this->addressCity;
    }

    /**
     * @param string $addressCity
     *
     * @return Competition
     */
    public function setAddressCity(string $addressCity): Competition
    {
        $this->addressCity = $addressCity;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     *
     * @return Competition
     */
    public function setPhone(string $phone): Competition
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return array
     */
    public function getServices(): array
    {
        return $this->services;
    }

    /**
     * @param array $services
     *
     * @return Competition
     */
    public function setServices(array $services): Competition
    {
        $this->services = $services;

        return $this;
    }

    /**
     * @return Trial[]
     */
    public function getTrials(): array
    {
        return $this->trials;
    }

    /**
     * @param Trial[] $trials
     *
     * @return Competition
     */
    public function setTrials(array $trials): Competition
    {
        $this->trials = $trials;

        return $this;
    }

    public function getCountTrials(): int
    {
        return count($this->trials);
    }
}
