<?php

namespace App\Mail\Blocks;

use App\Account\Models\User;
use App\Core\Blocks\AbstractBlock;

class MailBlock extends AbstractBlock
{
    protected $fileRender = 'mail/mail';
    protected $userModel;

    public function setUser(User $user): self
    {
        $this->userModel = $user;

        return $this;
    }

    public function getUser(): User
    {
        return $this->userModel;
    }
}
