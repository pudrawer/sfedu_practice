<?php

namespace App\Blocks;

use App\Models\AbstractCarModel;
use App\Models\User;

class MailBlock extends AbstractBlock
{
    protected $fileRender = 'mail/mail';
    protected $userModel;

    public function setChildModels(User $user): self
    {
        $this->userModel = $user;

        return $this;
    }

    public function getChildModels(): User
    {
        return $this->userModel;
    }

    public function renderMail(): string
    {
        ob_start();
        require APP_ROOT . "/App/Views/{$this->fileRender}.phtml";

        $result = ob_get_contents();
        ob_end_clean();
        return $result ?? '';
    }
}
