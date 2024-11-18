<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * This software is licensed under the MIT License. See the LICENSE file for more details.
 */

use Symfony\Component\Dotenv\Dotenv;

require \dirname(__DIR__) . '/vendor/autoload.php';

if (\file_exists(\dirname(__DIR__) . '/config/bootstrap.php')) {
    require \dirname(__DIR__) . '/config/bootstrap.php';
} elseif (\method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv)->bootEnv(\dirname(__DIR__) . '/.env');
}

if ($_SERVER['APP_DEBUG']) {
    \umask(0o000);
}
