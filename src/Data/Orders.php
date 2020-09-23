<?php

declare(strict_types=1);

namespace Bridges\Data;

use Bridges\Types\Types;
use Cake\Collection\Collection;
use Cake\Collection\CollectionInterface;

use function array_group_by;

/**
 * A data table renderable.
 */
class Orders
{
    /**
     * @param  iterable        $orders
     * @param  callable|string $grouping
     * @return iterable
     */
    public static function groupBy(iterable $orders, $grouping)
    {
        if (!($orders instanceof CollectionInterface)) {
            $orders = new Collection($orders);
        }

        return $orders->groupBy($grouping);
    }
}
