<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\ProfileInfoBlock;
use App\Exception\Exception;
use App\Models\Recourse\UserRecourse;
use App\Models\Session\Session;
use App\Models\User;

class ProfileInfoController extends AbstractController
{
    public function execute(): BlockInterface
    {
        $idParam = Session::getInstance()->getUserId();
        if (!$idParam) {
            $this->redirectTo('login');
        }
        $userModel = new User($idParam);

        if ($this->isGetMethod()) {
            $block = new ProfileInfoBlock();

            $userRecourse = new UserRecourse();

            return $block
                ->setHeader(['PROFILE'])
                ->setChildModels($userRecourse->getInfo($userModel))
                ->render('main');
        }

        if (!$this->updateInfo($userModel)) {
            throw new Exception();
        }
        $this->redirectTo('profileInfo');
    }

    private function updateInfo(User $userModel): bool
    {
        $emailParam   = htmlspecialchars($this->getPostParam('email'));
        $passParam    = htmlspecialchars($this->getPostParam('pass'));
        $nameParam    = htmlspecialchars($this->getPostParam('name'));
        $surnameParam = htmlspecialchars($this->getPostParam('surname'));
        $phoneParam   = htmlspecialchars($this->getPostParam('phone'));

        if (!$emailParam || !$nameParam || !$surnameParam || !$phoneParam) {
            throw new Exception();
        }

        $userModel
            ->setEmail($emailParam)
            ->setName($nameParam)
            ->setSurname($surnameParam)
            ->setPhone($phoneParam);

        $userRecourse = new UserRecourse();
        if (!$passParam) {
            return $userRecourse->updateInfo($userModel);
        }

        return $userRecourse->updateInfo($userModel->setPassword($passParam));
    }
}
