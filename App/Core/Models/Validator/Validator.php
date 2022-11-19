<?php

namespace App\Core\Models\Validator;

use App\Core\Exception\Exception;

class Validator
{
    public function checkName(string $param): self
    {
        $pregPattern = '/^[a-zа-я]+$/ui';
        if (!preg_match($pregPattern, $param)) {
            throw new Exception('Bad name');
        }

        return $this;
    }

    public function checkYear(string $param): self
    {
        $pregPattern = '/[1-2][0-9]{3}/ui';
        if (!preg_match($pregPattern, $param)) {
            throw new Exception('Bad year');
        }

        return $this;
    }

    public function checkEmail(string $param): self
    {
        $pregPattern = '/^[\w\d_.+-]+@[\w\d-]+.[\w]+$/ui';
        if (!preg_match($pregPattern, $param)) {
            throw new Exception('Bad email');
        }

        return $this;
    }

    public function checkPhoneNumber(string $param): self
    {
        $pregPattern = '/((8|\+7)-?)?\(?\d{3,5}\)?-?\d{1}-?\d{1}-?\d{1}-?\d{1}-?\d{1}((-?\d{1})?-?\d{1})?/';
        if (!preg_match($pregPattern, $param)) {
            throw new Exception();
        }

        return $this;
    }
}
