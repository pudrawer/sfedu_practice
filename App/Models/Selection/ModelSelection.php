<?php

namespace App\Models\Selection;

use App\Exception\Exception;
use App\Models\Brand;
use App\Models\Line;

class ModelSelection
{
    public static function selectBrandData(array $haystack): array
    {
        $hasNeededData =
            $haystack['brandName']
            && $haystack['carBrandId']
            && $haystack['countryName']
            && $haystack['countryId'];

        if ($hasNeededData) {
            $brand = new Brand();
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

        throw new Exception();
    }

    public static function selectLineData(array $haystack): ?array
    {
        $hasNeededData = $haystack['lineName'] && $haystack['lineId'];

        if ($hasNeededData) {
            $line = new Line();
            $line
                ->setId($haystack['lineId'])
                ->setName($haystack['lineName'])
            ;

            unset($haystack['lineId']);
            unset($haystack['lineName']);

            return ['model' => $line, 'data' => $haystack];
        }

        throw new Exception();
    }
}
