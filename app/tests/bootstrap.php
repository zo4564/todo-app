<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}

// ...
passthru('./bin/console --env=test doctrine:schema:drop --full-database --force');
passthru('./bin/console --env=test --no-interaction doctrine:migrations:migrate');
