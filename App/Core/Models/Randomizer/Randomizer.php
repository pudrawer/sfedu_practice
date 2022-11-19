<?php

namespace App\Core\Models\Randomizer;

class Randomizer
{
    /**
     * @param int|null $data
     * @return string
     * @throws \Exception
     */
    public function generateCsrfToken(int $data = 0): string
    {
        $token = '';

        if (!$data) {
            $token = hash('sha256', random_int(
                PHP_INT_MIN + 1,
                PHP_INT_MAX - 1
            ));
        } else {
            $token = hash('sha256', $data);
        }

        return $token;
    }
}
