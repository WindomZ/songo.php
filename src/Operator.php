<?php declare(strict_types=1);

namespace Songo;

class Operator
{
    public static function verifyQueryOperator(string $value): bool
    {
        switch ($value) {
            case '$and':
            case '$or':
            case '$eq':
            case '$ne':
            case '$lt':
            case '$lte':
            case '$gt':
            case '$gte':
            case '$like':
            case '$in':
            case '$nin':
            case '$bt':
            case '$nbt':
                return true;
            default:
                return false;
        }
    }
}
