<?php

declare(strict_types=1);

namespace Bridges;

use Dotenv\Dotenv;

define('Bridges\BASE_PATH', dirname(__DIR__));
define('Bridges\SRC_PATH', BASE_PATH . '/src');

$dotenv = Dotenv::createImmutable(BASE_PATH);
$dotenv->load();
$dotenv->required('DATA_DIR_PATH')->notEmpty();
