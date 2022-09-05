<?php

namespace App\Blocks;

abstract class AbstractBlock implements BlockInterface
{
    protected $data = [];
    protected $fileRender;
    protected $header = [];
    protected $commonStylesheetList = [
        'button/button.css',
        'common/reset.css',
        'common/common.css',
        'nav/nav.css',
        'logo/logo.css',
        'header-footer-section/header-footer-section.css',
    ];
    protected $childStylesheetList = [];

    protected $viewsPath = APP_ROOT . '/App/Views';
    protected $srcPath = APP_ROOT . '/src';

    public function getHeader(string $separator = '&nbsp;'): string
    {
        return implode($separator, $this->header);
    }

    public function renderChildBlock()
    {
        require "$this->viewsPath/$this->fileRender.phtml";
    }

    public function footerSetData(): array
    {
        return [
            'quickLinks' => [
                'main',
                'main',
                'main',
                'main',
            ],
            'pageLinks' => [
                'main',
                'main',
                'main',
                'main',
            ],
        ];
    }

    public function commonRender(
        string $activeLink
    ) {
        $headerBlock = new HeaderBlock();
        $footerBlock = new FooterBlock();

        $this->childStylesheetList = array_merge(
            $this->getStylesheetList(),
            $this->commonStylesheetList,
            $headerBlock->getStylesheetList(),
            $footerBlock->getStylesheetList()
        );

        $footerBlock->setData($this->footerSetData());

        $headerBlock->setActiveLink($activeLink);

        require "$this->viewsPath/Components/layout.phtml";
    }

    public function getStylesheetList(): array
    {
        return $this->childStylesheetList;
    }
}
