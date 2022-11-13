<?php

namespace App\Models\Selection;

use App\Exception\Exception;
use App\Exception\SelectionException;
use App\Models\Brand;

class BrandSelection extends AbstractSelection
{
    public function selectData(array $haystack): array
    {
        $hasNeededData =
            $haystack['brandName']
            && $haystack['carBrandId']
            && $haystack['countryName']
            && $haystack['countryId'];

        if ($hasNeededData) {
            $brand = $this->di->get(Brand::class);
            $brand
                ->setId($haystack['carBrandId'])
                ->setName($haystack['brandName'])
                ->setCountryName($haystack['countryName'])
                ->setCountryId($haystack['countryId'])
            ;

            unset($haystack['carBrandId']);
            unset($haystack['brandName']);
            unset($haystack['countryName']);
            unset($haystack['countryId']);

            return ['model' => $brand, 'data' => $haystack];
        }

        throw new SelectionException('Bad selection data' . PHP_EOL);
    }
}
