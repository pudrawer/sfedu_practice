<?php

namespace App\Models;

class Model extends AbstractCarModel
{
    private $year;
    private $previousId;
    private $previousName;
    private $lineId;

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setPreviousId(int $id): self
    {
        $this->previousId = $id;

        return $this;
    }

    public function getPreviousId(): int
    {
        return $this->previousId;
    }

    public function setPreviousName(string $name): self
    {
        $this->previousName = $name;

        return $this;
    }

    public function getPreviousName(): string
    {
        return $this->previousName;
    }

    public function __toString()
    {
        return 'Model';
    }

    public function setLineId(int $lineId): self
    {
        $this->lineId = $lineId;

        return $this;
    }

    public function getLineId(): int
    {
        return $this->lineId;
    }
}
