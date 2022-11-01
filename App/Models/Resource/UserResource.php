<?php

namespace App\Models\Resource;

use App\Database\Database;
use App\Exception\ResourceException;
use App\Models\User;

class UserResource extends AbstractResource
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
            throw new ResourceException('Bad user id' . PHP_EOL);
        }

        return $userModel
            ->setName($userInfo['name'])
            ->setSurname($userInfo['surname'])
            ->setPhone($userInfo['phone'])
            ->setEmail($userInfo['email']);
    }

    public function updateInfo(User $userModel): bool
    {
        $passStr = '';
        $passAlias = [];
        if ($userModel->getPassword()) {
            $passStr = '`password` = :user_password,';
            $passAlias = [
                ':user_password' => password_hash(
                    $userModel->getPassword(),
                    PASSWORD_DEFAULT
                ),
            ];
        }

        $stmt = Database::getInstance()->prepare("
        UPDATE
            `user`
        SET
            `email`    = :user_email,
            `name`     = :user_name,
            `surname`  = :user_surname,
            $passStr
            `phone`    = :user_phone
        WHERE `id` = :user_id LIMIT 1;
        ");

        $paramAlias = [
            ':user_email'    => $userModel->getEmail(),
            ':user_name'     => $userModel->getName(),
            ':user_surname'  => $userModel->getSurname(),
            ':user_phone'    => $userModel->getPhone(),
            ':user_id'       => $userModel->getId(),
        ];
        $stmt = $this->bindParamByMap($stmt, array_merge($paramAlias, $passAlias));

        return $stmt->execute();
    }

    public function getUserList(): array
    {
        $stmt = Database::getInstance()->prepare('
        SELECT 
            `id`, `name`, `surname`, `email`
        FROM
            `user`
        ');

        $stmt->execute();
        $result = $stmt->fetchAll();

        $modelList = [];

        foreach ($result as $value) {
            $temp = new User();
            $temp
                ->setId($value['id'])
                ->setName($value['name'])
                ->setSurname($value['surname'])
                ->setEmail($value['email']);

            $modelList[] = $temp;
        }

        return $modelList;
    }
}
