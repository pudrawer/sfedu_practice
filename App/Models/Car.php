<?php

namespace App\Models;

class Car extends AbstractCarModel
{
    private $num;
    private $year;
    private $vrc;
    private $userId;

    public function __toString(): string
    {
        return 'CarModel';
    }

    public function setNum(string $num): self
    {
        $this->num = $num;

        return $this;
    }

    public function getNum(): string
    {
        return $this->num;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setVrc(string $vrc): self
    {
        $this->vrc = $vrc;

        return $this;
    }

    public function getVrc(): string
    {
        return $this->vrc;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
