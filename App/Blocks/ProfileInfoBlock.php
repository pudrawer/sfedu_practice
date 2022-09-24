<?php

namespace App\Blocks;

use App\Models\User;

class ProfileInfoBlock extends AbstractBlock
{
    protected $fileRender = 'profile';
    protected $activeLink = 'main';
    protected $childStylesheetList = [
        'profile/profile.css',
        'profile-nav/profile-nav.css',
    ];

    protected $childModels = [];

    public function setChildModels(User $userModel): self
    {
        $this->childModels["$userModel"] = $userModel;

        return $this;
    }

    public function getChildModels(): array
    {
        return $this->childModels;
    }
}
