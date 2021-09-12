<?php

declare(strict_types=1);

namespace Bridges;

use Bridges\Types\Type;
use Bridges\Types\Types;
use Error;

$database = [
    'places' => null,
    'orders' => null,
];

foreach ($database as $key => $data) {
    $filename = $key . '.json';
    $filepath = BASE_PATH . $_ENV['DATA_DIR_PATH'] . '/' . $filename;

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

$colLabels = [
    'check'       => '<abbr title="Completion">' . $columns['check'] . '</abbr>',
    'number'      => '<abbr title="Order">' . $columns['number'] . '</abbr>',
] + $columns;

$rowLabels = [
    'number'      => 'Order',
] + $columns;

$thead = <<<THEAD
                    <colgroup>
                        <col class="c-col-check" />
                        <col class="c-col-number" />
                        <col class="c-col-name" />

THEAD;

if (!isset($_GET['grouped'])) {
    $thead .= <<<THEAD
                            <col class="c-col-origin" />

    THEAD;
}

$thead .= <<<THEAD
                        <col class="c-col-collection" />
                        <col class="c-col-delivery" />
                        <col class="c-col-objectives" />
                        <col class="c-col-category" />
                        <col class="c-col-maxlikes" />
                        <col class="c-col-weight" />
                        <col class="c-col-cargo" />
                    </colgroup>
                    <thead class="o-table-fixed">
                        <tr>
                            <th scope="col" class="o-tc c-tc-check" id="{% tableID %}-col-check" aria-describedby="refnote-order_completion">{$colLabels['check']}</th>
                            <th scope="col" class="o-tc c-tc-number" id="{% tableID %}-col-number" aria-describedby="refnote-order_number">{$colLabels['number']}</th>
                            <th scope="col" class="o-tc o-tc-trunc c-tc-name" id="{% tableID %}-col-name">{$colLabels['name']}</th>

THEAD;

if (!isset($_GET['grouped'])) {
    $thead .= <<<THEAD
                                <th scope="col" class="o-tc o-tc-trunc c-tc-origin" id="{% tableID %}-col-origin">{$colLabels['available']}</th>

    THEAD;
}

$thead .= <<<THEAD
                            <th scope="col" class="o-tc o-tc-trunc c-tc-collection" id="{% tableID %}-col-collection">{$colLabels['collection']}</th>
                            <th scope="col" class="o-tc o-tc-trunc c-tc-delivery" id="{% tableID %}-col-delivery">{$colLabels['delivery']}</th>
                            <th scope="col" class="o-tc o-tc-trunc c-tc-objectives" id="{% tableID %}-col-objectives" aria-describedby="refnote-objectives">{$colLabels['objectives']}</th>
                            <th scope="col" class="o-tc o-tc-trunc c-tc-category" id="{% tableID %}-col-category" aria-describedby="refnote-category">{$colLabels['category']}</th>
                            <th scope="col" class="o-tc c-tc-maxlikes" id="{% tableID %}-col-maxlikes" aria-describedby="refnote-maxlikes">{$colLabels['maxlikes']}</th>
                            <th scope="col" class="o-tc c-tc-weight" id="{% tableID %}-col-weight" aria-describedby="refnote-weight">{$colLabels['weight']}</th>
                            <th scope="col" class="o-tc c-tc-cargo" id="{% tableID %}-col-cargo" aria-describedby="refnote-cargo">{$colLabels['cargo']}</th>
                        </tr>
                    </thead>

THEAD;

if (!isset($_GET['grouped'])) {
    $tableId = 'ds-orders-by-number';

    echo <<<TABLE
                <div role="region" aria-labelledby="{$tableId}-caption" tabindex="0" class="o-table-container o-table-container-x o-table-container-y">
                    <table id="{$tableId}" class="o-table-spaced js-orders">
                        <caption id="{$tableId}-caption" class="u-screen-reader-text">🔢 All Orders</caption>

    TABLE;

    echo strtr($thead, [
        '{% tableID %}' => $tableId,
    ]);

    echo <<<TABLE
                        <tbody>

    TABLE;
}

foreach ($ordersByOrigin as $placeId => $orders) {
    $place = $placesById[$placeId];

    $pid = strtok($placeId, '-');

    if (isset($_GET['grouped'])) {
        $tableId = 'ds-orders-from-' . $pid;

        echo <<<TABLE
                    <div role="region" aria-labelledby="{$tableId}-caption" tabindex="0" class="o-table-container o-table-container-x">
                        <table id="{$tableId}" class="o-table-spaced js-orders">
                            <caption id="{$tableId}-caption">🚚 {$place->name}</caption>

        TABLE;

        echo strtr($thead, [
            '{% tableID %}' => $tableId,
        ]);

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
        $row['number'] = '<data class="c-data-number u-font-mono" value="' . $oid . '" data-type="' . Types::ORDER_STANDARD . '">' . ($order->bson ?: 'N/A') . '</data>';
        $row['name']   = $order->name;

        $from = $placesById[$order->available_at->place_id];
        $dest = $placesById[$order->delivery_at->place_id];

        $row['available'] = '🚚 <data class="c-data-available" value="' . ($from->bsln ?? $from->id) . '" data-type="' . $from->type . '">' . $from->name . '</data>';
        $row['delivery']  = '📍 <data class="c-data-delivery" value="' . ($dest->bsln ?? $dest->id) . '" data-type="' . $dest->type . '">' . $dest->name . '</data>';

        if (isset($order->collection_at)) {
            $collect = $order->collection_at;
            if (isset($collect->place_id)) {
                $collect = $placesById[$collect->place_id];
            }

            $row['collection'] = '📦 <data class="c-data-collection" value="' . ($collect->bsln ?? $collect->id) . '" data-type="' . $collect->type . '">' . $collect->name . '</data>';
        } else {
            $row['collection'] = '📦 <data class="c-data-collection" value="' . ($from->bsln ?? $from->id) . '" data-type="' . $from->type . '">' . $from->name . '</data>';
        }

        $row['objectives'] = htmlspecialchars($order->objectives_text, ENT_HTML5);
        $row['category']   = Type::getConst($order->category);

        if (is_string($order->maxlikes)) {
            $attr = html_build_attributes([
                'class'     => [ 'c-data-weight', 'u-font-mono' ],
                'data-type' => [ Types::ATTR_WEIGHT ],
            ]);

            $row['maxlikes'] = '<span ' . $attr . '>≈' . $order->maxlikes . '</span>';
        } elseif (is_object($order->maxlikes)) {
            $maxlikes = [];

            $delivStd = $order->maxlikes->standard;
            $delivPrm = $order->maxlikes->premium;

            if ($order->maxlikes->standard) {
                $attr = html_build_attributes([
                    'class'     => [ 'c-data-maxlikes', 'c-data-standard', 'u-font-mono' ],
                    'value'     => $order->maxlikes->standard,
                    'data-type' => [ Types::ATTR_LIKES, Types::DELIV_STANDARD ],
                ]);

                $maxlikes[] = '<data ' . $attr . '>≈' . number_format($order->maxlikes->standard) . '</data>';
            }

            if ($order->maxlikes->premium) {
                $attr = html_build_attributes([
                    'class'     => [ 'c-data-maxlikes', 'c-data-premium', 'u-font-mono' ],
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
                'class'     => [ 'c-data-weight', 'u-font-mono' ],
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
                    'class'     => [ 'c-data-load', 'u-font-mono' ],
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
                                <tr id="ds-order-{$oid}" class="c-tr-todo" data-order-id="{$oid}" data-order-num="{$order->bson}">
                                    <td class="o-tc c-tc-check" data-label="{$rowLabels['check']}">
                                        <label for="ds-order-select-{$oid}">
                                            <span class="u-screen-reader-text">{$row['check']}</span>
                                            <input id="ds-order-select-{$oid}" type="checkbox" name="order[]" value="{$oid}">
                                        </label>
                                    </td>
                                    <th scope="row" class="o-tc c-tc-number" data-label="{$rowLabels['number']}" id="ds-order-{$oid}-number">{$row['number']}</th>
                                    <th class="o-tc o-tc-trunc c-tc-name" data-label="{$rowLabels['name']}" id="ds-order-{$oid}-name">{$row['name']}</gh>

        ROW;

        if (!isset($_GET['grouped'])) {
            echo <<<ROW
                                        <td class="o-tc o-tc-trunc c-tc-origin" data-label="{$rowLabels['available']}">{$row['available']}</td>

            ROW;
        }

        echo <<<ROW
                                    <td class="o-tc o-tc-trunc c-tc-collection" data-label="{$rowLabels['collection']}">{$row['collection']}</td>
                                    <td class="o-tc o-tc-trunc c-tc-delivery" data-label="{$rowLabels['delivery']}">{$row['delivery']}</td>
                                    <td class="o-tc o-tc-trunc c-tc-objectives" data-label="{$rowLabels['objectives']}">{$row['objectives']}</td>
                                    <td class="o-tc o-tc-trunc c-tc-category" data-label="{$rowLabels['category']}">{$row['category']}</td>
                                    <td class="o-tc c-tc-maxlikes" data-label="{$rowLabels['maxlikes']}">{$row['maxlikes']}</td>
                                    <td class="o-tc c-tc-weight" data-label="{$rowLabels['weight']}">{$row['weight']}</td>
                                    <td class="o-tc c-tc-cargo" data-label="{$rowLabels['cargo']}">{$row['cargo']}</td>
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
