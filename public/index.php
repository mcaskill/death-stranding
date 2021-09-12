<?php

declare(strict_types=1);

namespace Bridges;

use Bridges\Types\Type;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/bootstrap.php';

$title  = App::formatDocumentTitle('Standard Orders Tracker');
$desc   = 'A tool for managing deliveries in the video game Death Stranding.';
$debug  = false;
$search = true;

$bodyAttrs = [
    'class' => [],
];

if ($debug) {
    $bodyAttrs['class'][] = 'o-show-layout';
}

if ($search) {
    $bodyAttrs['class'][] = 'has-search-form';
}

?><!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />

        <title><?php echo $title; ?></title>

        <meta name="description" content="<?php echo $desc; ?>" />

        <link rel="stylesheet" type="text/css" href="assets/styles/main.css" />
        <link rel="icon" type="image/svg+xml" href="favicon.svg" />
    </head>
    <body <?php echo html_build_attributes($bodyAttrs); ?>>
        <header>
            <h1><?php echo $title; ?></h1>
            <blockquote>
                <p><?php echo App::QUOTE; ?></p>
            </blockquote>
            <p><?php echo App::SUMMARY; ?> <a href="/about">Read more ‣</a></p>
            <p>Last updated <time datetime="<?php echo App::VERSION; ?>"><?php echo App::getVersionAsDateTimeString(); ?></time></p>
        </header>

        <main>
            <details>
                <summary>Notes</summary>
                <div class="o-body">
<?php

            include SRC_PATH . '/notes.php';

?>
                </div>
            </details>

<?php

            if ($search) {

?>
            <form id="ds-search" class="c-search-form js-search" role="search" method="get" accept-charset="UTF-8">
                <label for="ds-search-input" class="u-screen-reader-text">Filter orders</label>
                <input id="ds-search-input" class="c-search-input" name="query" type="text" value="" placeholder="Filter orders" autocomplete="off">
                <svg class="c-search-query-icon" version="1.1" viewBox="0 0 24 24" width="16" height="16" fill="none" aria-hidden="true">
                    <path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <button class="c-search-reset" type="reset" aria-label="Clear filters" hidden>
                    <svg class="c-search-reset-icon" version="1.1" viewBox="0 0 16 16" width="16" height="16" aria-hidden="true">
                        <path fill="currentColor" d="M3.72 3.72a.75.75 0 011.06 0L8 6.94l3.22-3.22a.75.75 0 111.06 1.06L9.06 8l3.22 3.22a.75.75 0 11-1.06 1.06L8 9.06l-3.22 3.22a.75.75 0 01-1.06-1.06L6.94 8 3.72 4.78a.75.75 0 010-1.06z"></path>
                    </svg>
                </button>
                <div class="c-search-autocomplete-dropdown js-search-results" role="menu">
                    <div class="c-search-autocomplete-results">
                    </div>
                    <div class="c-search-autocomplete-hints">
                    </div>
                </div>
                <div id="ds-status" class="c-search-status" role="status" aria-live="polite"></div>
            </form>
<?php

            }

            include SRC_PATH . '/orders.php';

?>
        </main>

        <script type="module" src="assets/scripts/main.js"></script>
    </body>
</html>
