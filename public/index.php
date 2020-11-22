<?php

declare(strict_types=1);

use Bridges\App;
use Bridges\Types\Type;

require '../vendor/autoload.php';

$title  = App::formatDocumentTitle('Standard Orders');
$debug  = false;
$search = true;

$bodyAttrs = [
    'class' => [],
];

if ($debug) {
    $bodyAttrs['class'][] = 'show-layout';
}

if ($search) {
    $bodyAttrs['class'][] = 'has-search-form';
}

?><!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8" />
        <meta content="width=device-width,initial-scale=1" name="viewport" />

        <title><?php echo $title; ?></title>

        <link rel="stylesheet" type="text/css" href="site.css" />
        <link rel="icon" type="image/x-icon" href="favicon.ico" />
    </head>
    <body <?php echo html_build_attributes($bodyAttrs); ?>>
        <header>
            <h1><?php echo $title; ?></h1>
            <blockquote>
                <p><?php echo App::QUOTE; ?></p>
            </blockquote>
            <p><?php echo App::DESCRIPTION; ?> <a href="/about">Read more ‣</a></p>
            <p>Last updated <time datetime="<?php echo App::VERSION; ?>"><?php echo App::getVersionAsDateTimeString(); ?></time></p>
        </header>

        <main>
            <details>
                <summary>Legend</summary>
                <div class="reflist columns">
                    <ul class="references">
                        <li id="refnote-order_completion"><dfn>Order Completion</dfn>: If your browsers local storage API is available, you can keep track of completed orders.</li>
                        <li id="refnote-order_number"><dfn>Order Number</dfn>: The Standard Order number.</li>
                        <li id="refnote-reorder"><dfn>Re-Orders</dfn>: Certain Standard Orders will only become available to let you "replay" some of <em>Sam's Orders</em> after you have beaten the game.</li>
                        <li id="refnote-objectives"><dfn>Objectives</dfn>: This includes both <em>regular</em> &amp; <em>premium</em> deliveries, marked in parenthesis. If one will grant Materials to a destination, look for the Material designation in the Objectives tab.</li>
                        <li id="refnote-category"><dfn>Category</dfn>: This is the Porter Grade category the order will add <em>likes</em> to.</li>
                        <li id="refnote-maxlikes"><dfn>Likes</dfn>: The maximum number of likes for a regular and premium delivery.</li>
                        <li id="refnote-weight"><dfn>Weight</dfn>: The total weight of the delivery.</li>
                        <li id="refnote-cargo"><dfn>Size</dfn>: The amount of cargo and size of cargo. i.e. M-10 = Medium x10. This is to assist with maximum cargo that can be load on Sam's back, or in a truck.</li>
                        <li id="refnote-chiral_waste"><dfn>Chiral Waste</dfn>: Cargo that has been contaminated by chiralium, and subsequently abandoned. Should be disposed at the bottom of a crater lake.</li>
                        <li id="refnote-undefined"><dfn>Undefined (<?php echo Type::UNDEFINED_VALUE; ?>)</dfn>: This symbol indicates that a value is missing or broken.</li>
                        <li id="refnote-remote_area"><dfn>Delivery to Remote Area</dfn>: Watch your footing. The cargo needs to be delivered to a remote and inhospitable area.</li>
                        <li id="refnote-mountainous"><dfn>Delivery to Mountainous Area</dfn>: This is a difficult delivery in a mountainous area. Watch out for slopes and inclines.</li>
                        <li id="refnote-snowy_mountain"><dfn>Delivery to Snowy Mountain Area</dfn>: This is a difficult delivery to a snowy, mountainous area. Be sure to take precautions against the cold weather.</li>
                        <li id="refnote-cargo_collection"><dfn>Cargo Collection</dfn>: Pick up the cargo from the collection point.</li>
                        <li id="refnote-cargo_collection_vog"><dfn>Cargo Collection from the Vog</dfn>: Watch out for the toxic gas known as "vog" in the area from which the cargo must be collected.</li>
                        <li id="refnote-cargo_collection_bt"><dfn>Cargo Collection from a BT Area</dfn>: Watch out for BTs. They have been known to appear in the area from which the cargo must be collected.</li>
                        <li id="refnote-cargo_recovery_mule"><dfn>Cargo Recovery from a MULE Camp</dfn>: Watch out for MULEs. The cargo must be recovered from one of their camps.</li>
                        <li id="refnote-cargo_recovery_terrorist"><dfn>Cargo Recovery from Terrorists</dfn>: Watch out for terrorists in the area from which the cargo must be recovered.</li>
                        <li id="refnote-cargo_fragile_1"><dfn>Fragile Cargo</dfn>: This cargo has low durability, and can be destroyed very easily. Try to avoid vibration and impact.</li>
                        <li id="refnote-cargo_fragile_2"><dfn>Fragile Cargo (Alt)</dfn>: Cargo must be carried in the hands at all times. Contents are extremely delicate. Even minor jolts and vibrations can cause damage.</li>
                        <li id="refnote-chilled_delivery_1"><dfn>Chilled Delivery</dfn>: High atmospheric temperatures and thermal pads will damage this cargo.</li>
                        <li id="refnote-chilled_delivery_2"><dfn>Chilled Delivery (Alt)</dfn>: "Chilled delivery" cargo will slowly deteriorate in warm areas. To keep such cargo in good condition, try to remain in sheltered areas and avoid direct sunlight. Exposing the cargo to rain and snow also helps to keep it cool.</li>
                        <li id="refnote-do_not_submerge"><dfn>Do Not Submerge</dfn>: Do not submerge  this cargo in rivers or other bodies of water. Water can enter the container and cause damage to its contents. Be especially careful when crossing rivers and streams.</li>
                    </ul>
                </div>
            </details>

<?php

            if ($search) {

?>
            <form id="ds-search" class="ds-search c-search-form" role="search" method="get" accept-charset="UTF-8">
                <input id="ds-search-input" class="c-search-input" name="query" type="text" value="" placeholder="Filter orders" aria-label="Filter orders" autocomplete="off">
                <svg class="c-search-query-icon" version="1.1" viewBox="0 0 24 24" width="16" height="16" fill="none" aria-hidden="true"><path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <button class="c-search-reset" type="reset" aria-label="Clear filters" hidden>
                    <svg class="c-search-reset-icon" version="1.1" viewBox="0 0 16 16" width="16" height="16" aria-hidden="true"><path fill="currentColor" d="M3.72 3.72a.75.75 0 011.06 0L8 6.94l3.22-3.22a.75.75 0 111.06 1.06L9.06 8l3.22 3.22a.75.75 0 11-1.06 1.06L8 9.06l-3.22 3.22a.75.75 0 01-1.06-1.06L6.94 8 3.72 4.78a.75.75 0 010-1.06z"></path></svg>
                </button>
                <div class="c-search-autocomplete-dropdown">
                    <div class="c-search-autocomplete-results">
                    </div>
                </div>
            </form>
<?php

            }

            include '../src/orders.php'

?>
        </main>

        <script type="module" src="site.js"></script>
    </body>
</html>
