<?php

declare(strict_types=1);

use Bridges\App;
use Bridges\Types\Type;

require '../vendor/autoload.php';

$title = App::formatDocumentTitle('Information');
$desc  = 'About the deliveries tracker for the video game Death Stranding.';

?><!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8" />
        <meta content="width=device-width,initial-scale=1" name="viewport" />

        <title><?php echo $title; ?></title>

        <meta name="description" content="<?php echo $desc; ?>" />

        <link rel="stylesheet" type="text/css" href="assets/styles/main.css" />
        <link rel="icon" type="image/svg+xml" href="favicon.svg" />
    </head>
    <body>
        <header>
            <h1><?php echo $title; ?></h1>
            <blockquote>
                <p><?php echo App::QUOTE; ?></p>
            </blockquote>
            <p><?php echo App::SUMMARY; ?> <a href="/">Return to index ‣</a></p>
            <p>Last updated <time datetime="<?php echo App::VERSION; ?>"><?php echo App::getVersionAsDateTimeString(); ?></time></p>
        </header>
        <main>
            <h3>Sources</h3>
            <ul>
                <li><a href="https://www.reddit.com/r/DeathStranding/comments/e3g1q8/full_standard_order_list_by_order_spoilers/" rel="noopener noreferrer nofollow">FULL Standard Order List by Order #, Part 1</a> by <a href="https://www.reddit.com/user/spenjer1/" rel="noopener noreferrer nofollow">@spenjer1</a>, <a href="https://www.reddit.com/user/miloby/" rel="noopener noreferrer nofollow">@Miloby</a>, and <a href="https://www.reddit.com/u/Helel_Ben/" rel="noopener noreferrer nofollow">@Helel_Ben</a> on Reddit. Published 2019-11-29. Retrieved 2019-05-02.</li>
                <li><a href="https://www.reddit.com/r/DeathStranding/comments/e3g2yg/full_standard_order_list_by_order_part_2_spoilers/" rel="noopener noreferrer nofollow">FULL Standard Order List by Order #, Part 2</a> by <a href="https://www.reddit.com/user/spenjer1/" rel="noopener noreferrer nofollow">@spenjer1</a>, <a href="https://www.reddit.com/user/miloby/" rel="noopener noreferrer nofollow">@Miloby</a>, and <a href="https://www.reddit.com/u/Helel_Ben/" rel="noopener noreferrer nofollow">@Helel_Ben</a> on Reddit. Published 2019-11-29. Retrieved 2019-05-02.</li>
                <li><a href="https://www.reddit.com/r/DeathStranding/comments/e1ig81/spoilers_condensed_list_of_standard_orders/" rel="noopener noreferrer nofollow">Condensed List of Standard Orders</a> by <a href="https://www.reddit.com/user/spenjer1/" rel="noopener noreferrer nofollow">@spenjer1</a>, <a href="https://www.reddit.com/user/miloby/" rel="noopener noreferrer nofollow">@Miloby</a>, and <a href="https://www.reddit.com/u/Helel_Ben/" rel="noopener noreferrer nofollow">@Helel_Ben</a> on Reddit. Published 2019-11-25.</li>
                <li><a href="https://gamefaqs.gamespot.com/ps4/184428-death-stranding/faqs/78100" rel="noopener noreferrer nofollow">Standard Orders List</a> by <a href="https://www.reddit.com/user/spenjer1/" rel="noopener noreferrer nofollow">@spenjer1</a>, <a href="https://www.reddit.com/user/miloby/" rel="noopener noreferrer nofollow">@Miloby</a>, and <a href="https://www.reddit.com/u/Helel_Ben/" rel="noopener noreferrer nofollow">@Helel_Ben</a> on GameFAQs. Published 2019-12-12.</li>
                <li><a href="https://www.ign.com/wikis/death-stranding/List_of_Standard_Orders" rel="noopener noreferrer nofollow">List of Standard Orders</a> (IGN)</li>
                <li><a href="https://www.ign.com/maps/death-stranding/world" rel="noopener noreferrer nofollow">World Map</a> (IGN)</li>
                <li><a href="https://mapgenie.io/death-stranding/maps/world" rel="noopener noreferrer nofollow">World Map</a> (Map Genie)</li>
            </ul>

            <h3>Acknowledgements</h3>
            <ul>
                <li><a href="https://nessworthy.me/deathstranding/" rel="noopener noreferrer nofollow">Death Stranding Delivery Checker</a> by <a href="https://github.com/Nessworthy" rel="noopener noreferrer nofollow">Sean Nessworthy</a></li>
                <li><a href="https://github.com/wagawo/derivery-checker" rel="noopener noreferrer nofollow">Death Stranding Delivery Checker</a> by <a href="https://github.com/wagawo" rel="noopener noreferrer nofollow">@wagawo</a> and <a href="https://github.com/elriea2000" rel="noopener noreferrer nofollow">@elriea2000</a> — <br>Initial inspiration for this project.</li>
                <li><a href="https://github.com/smcnabb/death-stranding-zipline-network" rel="noopener noreferrer nofollow">Death Stranding Zipline Network Tool</a> by <a href="https://github.com/smcnabb" rel="noopener noreferrer nofollow">@smcnabb</a> — <br>Initial coordinates and three-letter facility codes.</li>
            </ul>
        </main>

        <footer>
            <h3>Legal</h3>
            <p>BRIDGES HQ is released to the Public Domain.</p>
            <p>DEATH STRANDING is a trademark of Sony Interactive Entertainment LLC. Created and developed by Kojima Productions. All trademarks are the property of their respective owners.</p>
            <p>This project is a fan resource and in no way affiliated with Sony or Kojima Productions.</p>
        </footer>
    </body>
</html>
