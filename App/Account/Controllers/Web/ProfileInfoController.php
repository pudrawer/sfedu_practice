<?php

namespace App\Account\Controllers\Web;

use App\Account\Blocks\ProfileInfoBlock;
use App\Account\Models\Resource\UserResource;
use App\Account\Models\User;
use App\Core\Blocks\BlockInterface;
use App\Core\Controllers\Web\AbstractController;
use App\Core\Exception\Exception;
use App\Core\Models\Environment;
use App\Core\Models\Session\Session;
use App\Core\Models\Validator\Validator;
use Laminas\Di\Di;

class ProfileInfoController extends AbstractController
{
    public function __construct(
        Di $di,
        Environment $env,
        ProfileInfoBlock $block,
        UserResource $resource,
        Validator $validator,
        Session $session,
        array $params = []
    ) {
        parent::__construct(
            $di,
            $env,
            $params,
            $resource,
            $block,
            $validator,
            $session
        );
    }

    public function execute(): BlockInterface
    {
        $idParam = $this->session->start()->getUserId();
        if (!$idParam) {
            $this->redirectTo('login');
        }
        $userModel = $this->di->get(User::class);
        $userModel->setId($idParam);

        if ($this->isGetMethod()) {
            return $this->block
                ->setHeader(['PROFILE'])
                ->setChildModels($this->resource->getInfo($userModel))
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

        $this->checkCsrfToken();

        if (!$emailParam || !$nameParam || !$surnameParam || !$phoneParam) {
            throw new Exception();
        }

        $this->validator
            ->checkEmail($emailParam)
            ->checkPhoneNumber($phoneParam)
            ->checkName($nameParam)
            ->checkName($surnameParam);

        $userModel
            ->setEmail($emailParam)
            ->setName($nameParam)
            ->setSurname($surnameParam)
            ->setPhone($phoneParam);

        if (!$passParam) {
            return $this->resource->updateInfo($userModel);
        }

        return $this->resource->updateInfo($userModel->setPassword($passParam));
    }
}
