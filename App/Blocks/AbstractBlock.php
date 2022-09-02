<?php

namespace App\Blocks;

abstract class AbstractBlock implements BlockInterface
{
    protected $data = [];
    protected $fileRender;
    protected $header = [];

    protected $viewsPath = APP_ROOT . '/App/Views';
    protected $srcPath = APP_ROOT . '/src';

    public function getHeader(): string
    {
        $headerStr = '';
        foreach ($this->header as $key => $item) {
            $headerStr .= $item;

            if ($key != array_key_last($this->header)) {
                $headerStr .= '&nbsp;';
            }
        }

        return $headerStr;
    }
}
