<?php

namespace App\Controllers\Web;

use App\Blocks\BlockInterface;
use App\Blocks\ProfileInfoBlock;
use App\Exception\Exception;
use App\Models\Environment;
use App\Models\Resource\UserResource;
use App\Models\Session\Session;
use App\Models\User;
use App\Models\Validator\Validator;
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
