<?php

namespace App\Models\Validator;

use App\Exception\Exception;

class Validator
{
    public function checkName(string $param): self
    {
        if (!preg_match('/^[a-zа-я]+$/ui', $param)) {
            throw new Exception('Bad name');
        }

        return $this;
    }

    public function checkYear(string $param): self
    {
        if (!preg_match('/[1-2][0-9]{3}/ui', $param)) {
            throw new Exception('Bad year');
        }

        return $this;
    }

    public function checkEmail(string $param): self
    {
        if (!preg_match('/^[\w\d_.+-]+@[\w\d-]+.[\w]+$/ui', $param)) {
            throw new Exception('Bad email');
        }

        return $this;
    }

    public function checkPhoneNumber(string $param): self
    {
        if (
            !preg_match(
                '/((8|\+7)-?)?\(?\d{3,5}\)?-?\d{1}-?\d{1}-?\d{1}-?\d{1}-?\d{1}((-?\d{1})?-?\d{1})?/',
                $param
            )
        ) {
            throw new Exception();
        }

        return $this;
    }
}
