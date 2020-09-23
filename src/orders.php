<?php

declare(strict_types=1);

namespace Bridges;

use Bridges\Types\Type;
use Bridges\Types\Types;

$database = [
    'places' => null,
    'orders' => null,
];

foreach ($database as $key => $data) {
    $filename = $key . '.json';
    $filepath = '../data/' . $filename;

    if (!file_exists($filepath)) {
        throw new Error('JSON: File [' . $filename . '] does not exist');
    }

    $json = file_get_contents($filepath);
    $data = json_decode($json);

    if ($data === null) {
        throw new Error('JSON: '.json_last_error_msg(), json_last_error());
    }

    $database[$key] = $data;
}

$placesById = [];
$placesByAlpha3 = [];
foreach ($database['places'] as $place) {
    $place->orders = [];

    $placesById[$place->id] = $place;

    if (!empty($place->alpha3)) {
        $placesByAlpha3[$place->alpha3] = $place->id;
    }
}

$ordersById = [];
$ordersByOrigin = [];
foreach ($database['orders'] as $order) {
    $ordersById[$order->id] = $order;
    $ordersByOrigin[$order->available_at->place_id][] = $order->id;
}

$columns = [
    'check'       => '✓',
    'number'      => '#',
    'name'        => 'Name',
    'available'   => 'Available at',
    'collection'  => 'Collect / Retrieve at',
    'delivery'    => 'Deliver to / Dispose at',
    'objectives'  => 'Objectives',
    'category'    => 'Category',
    'maxlikes'    => 'Max Likes',
    'weight'      => 'Weight',
    'cargo'       => 'Size',
];

$labels = [
    'number'      => 'Order',
] + $columns;

$thead = <<<THEAD
                    <colgroup>
                        <col class="col-check" />
                        <col class="col-number" />
                        <col class="col-name" />

THEAD;

if (!isset($_GET['grouped'])) {
    $thead .= <<<THEAD
                            <col class="col-origin" />

    THEAD;
}

$thead .= <<<THEAD
                        <col class="col-collection" />
                        <col class="col-delivery" />
                        <col class="col-objectives" />
                        <col class="col-category" />
                        <col class="col-maxlikes" />
                        <col class="col-weight" />
                        <col class="col-cargo" />
                    </colgroup>
                    <thead class="table-fixed font-mono">
                        <tr>
                            <th scope="col" class="tc tc-check" aria-describedby="refnote-order_completion">{$columns['check']}</th>
                            <th scope="col" class="tc tc-number" aria-describedby="refnote-order_number">{$columns['number']}</th>
                            <th scope="col" class="tc tc-trunc tc-name">{$columns['name']}</th>

THEAD;

if (!isset($_GET['grouped'])) {
    $thead .= <<<THEAD
                                <th scope="col" class="tc tc-trunc tc-origin">{$columns['available']}</th>

    THEAD;
}

$thead .= <<<THEAD
                            <th scope="col" class="tc tc-trunc tc-collection">{$columns['collection']}</th>
                            <th scope="col" class="tc tc-trunc tc-delivery">{$columns['delivery']}</th>
                            <th scope="col" class="tc tc-trunc tc-objectives" aria-describedby="refnote-objectives">{$columns['objectives']}</th>
                            <th scope="col" class="tc tc-trunc tc-category" aria-describedby="refnote-category">{$columns['category']}</th>
                            <th scope="col" class="tc tc-maxlikes" aria-describedby="refnote-maxlikes">{$columns['maxlikes']}</th>
                            <th scope="col" class="tc tc-weight" aria-describedby="refnote-weight">{$columns['weight']}</th>
                            <th scope="col" class="tc tc-cargo" aria-describedby="refnote-cargo">{$columns['cargo']}</th>
                        </tr>
                    </thead>

THEAD;

if (!isset($_GET['grouped'])) {
    echo <<<TABLE
                <div role="region" aria-labelledby="ds-orders-by-number" tabindex="0" class="table-container table-container-x table-container-y">
                    <table id="ds-orders-by-number" class="ds-orders table-spaced">
                        <caption id="ds-orders-by-number-caption" class="screen-reader-text">🔢 All Orders</caption>

    TABLE;

    echo $thead;

    echo <<<TABLE
                        <tbody>

    TABLE;
}

foreach ($ordersByOrigin as $placeId => $orders) {
    $place = $placesById[$placeId];

    $pid = strtok($placeId, '-');

    if (isset($_GET['grouped'])) {
        echo <<<TABLE
                    <div role="region" aria-labelledby="ds-orders-from-{$pid}-caption" tabindex="0" class="table-container table-container-x">
                        <table id="ds-orders-from-{$pid}" class="ds-orders table-spaced">
                            <caption id="ds-orders-from-{$pid}-caption">🚚 {$place->name}</caption>

        TABLE;

        echo $thead;

        echo <<<TABLE
                            <tbody>

        TABLE;
    }

    foreach ($orders as $orderId) {
        $order = $ordersById[$orderId];

        $row = array_fill_keys(array_keys($columns), '<span aria-describedby="refnote-undefined">' . Type::UNDEFINED_VALUE . '</span>');

        $oid = strtok($orderId, '-');
        $fid = strtok($order->available_at->place_id, '-');
        $did = strtok($order->delivery_at->place_id, '-');

        if ($order->bson) {
            $text = 'Order ' . $order->bson;
        } else {
            $text = $order->name;
        }

        $row['check']  = 'Mark ' . $text . ' as completed';
        $row['number'] = '<data class="data-number font-mono" value="' . $oid . '" data-type="' . Types::ORDER_STANDARD . '">' . ($order->bson ?: 'N/A') . '</data>';
        $row['name']   = $order->name;

        $from = $placesById[$order->available_at->place_id];
        $dest = $placesById[$order->delivery_at->place_id];

        $row['available'] = '🚚 <data class="data-available" value="' . ($from->bsln ?? $from->id) . '" data-type="' . $from->type . '">' . $from->name . '</data>';
        $row['delivery']  = '📍 <data class="data-delivery" value="' . ($dest->bsln ?? $dest->id) . '" data-type="' . $dest->type . '">' . $dest->name . '</data>';

        if (isset($order->collection_at)) {
            $collect = $order->collection_at;
            if (isset($collect->place_id)) {
                $collect = $placesById[$collect->place_id];
            }

            $row['collection'] = '📦 <data class="data-collection" value="' . ($collect->bsln ?? $collect->id) . '" data-type="' . $collect->type . '">' . $collect->name . '</data>';
        } else {
            $row['collection'] = '📦 <data class="data-collection" value="' . ($from->bsln ?? $from->id) . '" data-type="' . $from->type . '">' . $from->name . '</data>';
        }

        $row['objectives'] = htmlspecialchars($order->objectives_text, ENT_HTML5);
        $row['category']   = Type::getConst($order->category);

        if (is_string($order->maxlikes)) {
            $attr = html_build_attributes([
                'class'     => 'data-weight font-mono',
                'data-type' => [ Types::ATTR_WEIGHT ],
            ]);

            $row['maxlikes'] = '<span ' . $attr . '>≈' . $order->maxlikes . '</span>';
        } elseif (is_object($order->maxlikes)) {
            $maxlikes = [];

            $delivStd = $order->maxlikes->standard;
            $delivPrm = $order->maxlikes->premium;

            if ($order->maxlikes->standard) {
                $attr = html_build_attributes([
                    'class'     => [ 'data-maxlikes data-standard', 'font-mono' ],
                    'value'     => $order->maxlikes->standard,
                    'data-type' => [ Types::ATTR_LIKES, Types::DELIV_STANDARD ],
                ]);

                $maxlikes[] = '<data ' . $attr . '>≈' . number_format($order->maxlikes->standard) . '</data>';
            }

            if ($order->maxlikes->premium) {
                $attr = html_build_attributes([
                    'class'     => [ 'data-maxlikes data-premium', 'font-mono' ],
                    'value'     => $order->maxlikes->premium,
                    'data-type' => [ Types::ATTR_LIKES, Types::DELIV_PREMIUM ],
                ]);

                $maxlikes[] = '<data ' . $attr . '>≈' . number_format($order->maxlikes->premium) . '</data>';
            }

            if (count($maxlikes)) {
                $row['maxlikes'] = implode(' / ', $maxlikes);
            }
        }

        if (is_numeric($order->weight)) {
            $attr = html_build_attributes([
                'class'     => 'data-weight font-mono',
                'value'     => $order->weight,
                'data-type' => [ Types::ATTR_WEIGHT, Types::ATTR_MASS_KG ],
            ]);

            $row['weight'] = '<data ' . $attr . '>' . number_format($order->weight, 1) . '&nbsp;kg</data>';
        } else {
            $row['weight'] = $order->weight;
        }

        if ($order->content) {
            $content = [];
            foreach ($order->content as $load) {
                $parts = explode('-', $load);
                $attr  = html_build_attributes([
                    'class'     => 'data-load font-mono',
                    'title'     => $parts[1] . ' × ' . $parts[0],
                    'data-type' => [ Types::ATTR_CARGO ],
                ]);

                $content[] = '<data ' . $attr . '>' . $load . '</data>';
            }

            if (count($content)) {
                $row['cargo'] = implode('; ', $content);
            }
        }

        echo <<<ROW
                                <tr id="ds-order-{$oid}" class="ds-order tr-todo" data-order-id="{$oid}" data-order-num="{$order->bson}">
                                    <td class="tc tc-check" data-label="{$labels['check']}">
                                        <label for="cb-select-{$oid}">
                                            <span class="screen-reader-text">{$row['check']}</span>
                                            <input id="cb-select-{$oid}" type="checkbox" name="order[]" value="{$oid}">
                                        </label>
                                    </td>
                                    <th scope="row" class="tc tc-number" data-label="{$labels['number']}">{$row['number']}</th>
                                    <td class="tc tc-trunc tc-name" data-label="{$labels['name']}">{$row['name']}</td>

        ROW;

        if (!isset($_GET['grouped'])) {
            echo <<<ROW
                                        <td class="tc tc-trunc tc-origin" data-label="{$labels['available']}">{$row['available']}</td>

            ROW;
        }

        echo <<<ROW
                                    <td class="tc tc-trunc tc-collection" data-label="{$labels['collection']}">{$row['collection']}</td>
                                    <td class="tc tc-trunc tc-delivery" data-label="{$labels['delivery']}">{$row['delivery']}</td>
                                    <td class="tc tc-trunc tc-objectives" data-label="{$labels['objectives']}">{$row['objectives']}</td>
                                    <td class="tc tc-trunc tc-category" data-label="{$labels['category']}">{$row['category']}</td>
                                    <td class="tc tc-maxlikes" data-label="{$labels['maxlikes']}">{$row['maxlikes']}</td>
                                    <td class="tc tc-weight" data-label="{$labels['weight']}">{$row['weight']}</td>
                                    <td class="tc tc-cargo" data-label="{$labels['cargo']}">{$row['cargo']}</td>
                                </tr>

        ROW;
    } // $orders

    if (isset($_GET['grouped'])) {
        echo <<<TABLE
                            </tbody>
                        </table>
                    </div>

        TABLE;
    }
}

if (!isset($_GET['grouped'])) {
    echo <<<TABLE
                        </tbody>
                    </table>
                </div>

    TABLE;
}
