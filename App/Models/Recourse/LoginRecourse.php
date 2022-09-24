<?php

namespace App\Models\Recourse;

use App\Database\Database;

class LoginRecourse extends AbstractRecourse
{
    public function checkLogin(
        string $email,
        string $pass
    ): ?int {
        $connection = Database::getInstance();
        $stmt = $connection->prepare('
        SELECT 
            `id`, `password`
        FROM 
            `user`
        WHERE `email` = :email
        LIMIT 1;
        ');

        $stmt = $this->bindParamByMap($stmt, [
            ':email' => $email,
        ]);
        $stmt->execute();
        $result = $stmt->fetch();

        if (password_verify($pass, $result['password'])) {
            return $result['id'];
        }

        return null;
    }
}
