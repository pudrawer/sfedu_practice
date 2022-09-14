<?php

namespace App\Models\Modification;

abstract class AbstractCarModification implements InterfaceModification
{
    public function bindParamByMap(
        \PDOStatement $stmt,
        array $paramMap
    ): \PDOStatement {
        foreach ($paramMap as $alias => &$value) {
            $stmt->bindParam(
                $alias,
                $value,
                \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT
            );
        }

        return $stmt;
    }
}
