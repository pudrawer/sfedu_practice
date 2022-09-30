<?php

namespace App\Blocks;

use App\Models\Session\Session;

abstract class AbstractBlock implements BlockInterface
{
    protected $data = [];
    protected $fileRender;
    protected $header = [];
    protected $commonStylesheetList = [
        'button.css',
        'reset.css',
        'common.css',
        'nav.css',
        'logo.css',
        'header-footer-section.css',
        'form.css',
        'delete.css',
    ];
    protected $childStylesheetList = [];

    protected $viewsPath = APP_ROOT . '/App/Views';
    protected $srcPath = APP_ROOT . '/src';

    public function getHeader(string $separator = '&nbsp;'): string
    {
        return implode($separator, $this->header);
    }

    public function getStylesheetList(): array
    {
        return $this->childStylesheetList;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data): self
    {
        $this->data = $data;

        return $this;
    }

    public function setHeader(array $headerData): self
    {
        $this->header = $headerData;

        return $this;
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

    public function render(string $activeLink): self
    {
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

        return $this;
    }

    public function getCsrfToken(): string
    {
        return Session::getInstance()->getCsrfToken();
    }

    public function renderCsrfToken(): void
    {
        require_once APP_ROOT . '/App/Views/csrf-token-input.phtml';
    }

    public function normalizeData(string $data): string
    {
        return strip_tags($data);
    }
}
