<?php

namespace App\Models\Recourse;

use App\Database\Database;
use App\Exception\Exception;
use App\Models\User;

class UserRecourse extends AbstractRecourse
{
    public function getInfo(User $userModel): User
    {
        $stmt = Database::getInstance()->prepare('
        SELECT
            `email`,
            `phone`,
            `name`,
            `surname`
        FROM
            `user`
        WHERE `id` = :user_id;
        ');
        $stmt = $this->bindParamByMap($stmt, [
            ':user_id' => $userModel->getId(),
        ]);

        $stmt->execute();
        $userInfo = $stmt->fetch();

        if (!$userInfo) {
            throw new Exception('Bad user id' . PHP_EOL);
        }

        return $userModel
            ->setName($userInfo['name'])
            ->setSurname($userInfo['surname'])
            ->setPhone($userInfo['phone'])
            ->setEmail($userInfo['email']);
    }

    public function updateInfo(User $userModel): bool
    {
        $stmt = Database::getInstance()->prepare('
        UPDATE
            `user`
        SET
            `email`    = :user_email,
            `name`     = :user_name,
            `surname`  = :user_surname,
            `phone`    = :user_phone,
            `password` = :user_password
        WHERE `id` = :user_id LIMIT 1;
        ');

        $stmt = $this->bindParamByMap($stmt, [
            ':user_email'    => $userModel->getEmail(),
            ':user_name'     => $userModel->getName(),
            ':user_surname'  => $userModel->getSurname(),
            ':user_phone'    => $userModel->getPhone(),
            ':user_password' => $userModel->getPassword(),
            ':user_id'       => $userModel->getId(),
        ]);

        return $stmt->execute();
    }

    public function updateInfoWithoutPass(User $userModel): bool
    {
        $stmt = Database::getInstance()->prepare('
        UPDATE
            `user`
        SET
            `email`    = :user_email,
            `name`     = :user_name,
            `surname`  = :user_surname,
            `phone`    = :user_phone
        WHERE `id` = :user_id LIMIT 1;
        ');

        $stmt = $this->bindParamByMap($stmt, [
            ':user_email'   => $userModel->getEmail(),
            ':user_name'    => $userModel->getName(),
            ':user_surname' => $userModel->getSurname(),
            ':user_phone'   => $userModel->getPhone(),
            ':user_id'      => $userModel->getId(),
        ]);

        return $stmt->execute();
    }
}
