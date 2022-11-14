<?php

namespace App\Blocks;

use App\Models\AbstractCarModel;
use App\Models\User;

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
