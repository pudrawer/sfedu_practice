<?php

namespace App\Models\Service\Writer;

class CsvWriter extends AbstractWriter
{
    /**
     * @param array $data
     * @param int $mode array-type: 1(one-dimensional) | 2(two-dimensional)
     * @return void
     */
    public function write(array $data, int $mode = 1)
    {
        $output = fopen("{$this->filePath}.csv", 'w+');

        if ($mode == 1) {
            fputcsv($output, $data);
        } elseif ($mode == 2) {
            foreach ($data as $datum) {
                fputcsv($output, $datum);
            }
        }

        fclose($output);
    }
}
