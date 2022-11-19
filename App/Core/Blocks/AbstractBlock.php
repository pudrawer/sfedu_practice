<?php

namespace App\Core\Blocks;

use App\Core\Models\Session\Session;
use Laminas\Di\Di;

abstract class AbstractBlock implements BlockInterface
{
    protected $di;

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

    public function __construct(Di $di)
    {
        $this->di = $di;
    }

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

    public function renderChildBlock(string $fileRender = null): self
    {
        $fileRender = $fileRender ?: $this->fileRender;

        require "$this->viewsPath/$fileRender.phtml";
        return $this;
    }

    public function getTemplateHtml()
    {
        ob_start();
        require APP_ROOT . "/App/Views/{$this->fileRender}.phtml";

        $result = ob_get_contents();
        ob_end_clean();
        return $result ?? '';
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
        $headerBlock = $this->di->get(HeaderBlock::class);
        $footerBlock = $this->di->get(FooterBlock::class);

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

        return $this->di->get(Session::class)->getCsrfToken();
    }

    public function normalizeData(string $data): string
    {
        return strip_tags($data);
    }
}
