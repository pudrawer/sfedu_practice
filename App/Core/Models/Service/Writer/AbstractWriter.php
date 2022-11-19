<?php

namespace App\Core\Models\Service\Writer;

use const App\Models\Service\Writer\APP_ROOT;

abstract class AbstractWriter
{
    protected $filePath;

    public function __construct(string $fileName)
    {
        $currentTime = new \DateTime();
        $currentTime = date('d_m_Y__H_i', $currentTime->getTimestamp());
        $this->filePath = APP_ROOT . "/var/output/{$fileName}_{$currentTime}";
    }

    abstract public function write(array $data);
}
