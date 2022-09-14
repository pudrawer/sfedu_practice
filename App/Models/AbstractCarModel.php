<?php

namespace App\Models;

abstract class AbstractCarModel
{
    protected $id;
    protected $name;
    protected $countryName;
    protected $countryId;

    public function setCountryId(int $countryId): self
    {
        $this->countryId = $countryId;

        return $this;
    }

    public function getCountryId(): int
    {
        return $this->countryId;
    }

    public function setCountryName(string $countryName): self
    {
        $this->countryName = $countryName;

        return $this;
    }

    public function getCountryName(): string
    {
        return $this->countryName;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
