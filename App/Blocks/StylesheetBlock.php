<?php

namespace App\Blocks;

class StylesheetBlock extends AbstractBlock
{
    public function render(): self
    {
        $resultFileList = [];

        foreach ($this->data as $dir) {
            if ($dir == 'img') {
                continue;
            }

            $fileList = scandir("$this->srcPath/$dir");
            $fileList = array_slice($fileList, 2);

            foreach ($fileList as $file) {
                $resultFileList[] = "../../src/$dir/$file";
            }
        }

        $this->data = $resultFileList;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }
}
