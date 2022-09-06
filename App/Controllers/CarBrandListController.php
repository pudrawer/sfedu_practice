<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\BrandListBlock;
use App\Database\Database;

class CarBrandListController extends AbstractController
{
    public function execute(): BlockInterface
    {
        $block = new BrandListBlock();

        $connection = Database::getConnection();
        $stmt = $connection->query('SELECT * FROM `car_brand`;');

        return $block->setData([
            'page' => 'BRAND LIST',
            'data' => $stmt->fetchAll()
        ])->render();
    }
}