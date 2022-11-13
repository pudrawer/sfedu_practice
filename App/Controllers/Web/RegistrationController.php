<?php

namespace App\Controllers\Web;

use App\Blocks\BlockInterface;
use App\Blocks\ProfileInfoBlock;
use App\Blocks\RegistrationBlock;
use App\Exception\Exception;
use App\Models\Environment;
use App\Models\Randomizer\Randomizer;
use App\Models\Resource\AbstractResource;
use App\Models\Resource\RegistrationResource;
use App\Models\Session\Session;
use App\Models\Validator\Validator;
use Laminas\Di\Di;

class RegistrationController extends AbstractController
{
    public function __construct(
        Di $di,
        Environment $env,
        RegistrationBlock $block,
        RegistrationResource $resource,
        Validator $validator,
        Session $session,
        Randomizer $randomizer,
        array $params = []
    ) {
        parent::__construct(
            $di,
            $env,
            $params,
            $resource,
            $block,
            $validator,
            $session,
            $randomizer
        );
    }

    public function execute(): BlockInterface
    {
        if ($this->session->start()->getUserId()) {
            $this->redirectTo('profileInfo');
        }

        if ($this->isGetMethod()) {
            $this->session->setCsrfToken($this->randomizer->generateCsrfToken());

            return $this->block
                ->setHeader(['USER REGISTRATION'])
                ->render('main');
        }

        $this
            ->registerUser($this->alreadyRegister())
            ->redirectTo();


        throw new Exception('Already registered' . PHP_EOL);
    }

    private function alreadyRegister(): AbstractResource
    {
        $inputEmail = htmlspecialchars($this->getPostParam('email'));

        if (!$inputEmail) {
            throw new Exception();
        }

        if ($this->resource->checkRegistration($inputEmail)) {
            throw new Exception();
        }

        return $this->resource;
    }

    private function registerUser(AbstractResource $model): self
    {
        $inputEmail  = htmlspecialchars($this->getPostParam('email'));
        $inputPass   = htmlspecialchars($this->getPostParam('pass'));
        $inputRepass = htmlspecialchars($this->getPostParam('repass'));

        $this->checkCsrfToken();

        $hasRequiredData   = $inputEmail && $inputPass && $inputRepass;
        $hasEqualPasswords = $inputPass === $inputRepass;

        if (!$hasRequiredData || !$hasEqualPasswords) {
            throw new Exception();
        }

        $model->registerUser($inputEmail, $inputPass);

        return $this;
    }
}
