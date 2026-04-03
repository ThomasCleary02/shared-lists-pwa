<?php

/**
 * Router for `php -S` without Laravel's default access log to php://stdout.
 * That log runs before index.php and can trigger "headers already sent" on some hosts (e.g. Render).
 */
$publicPath = getcwd();

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

if ($uri !== '/' && file_exists($publicPath.$uri)) {
    return false;
}

require_once $publicPath.'/index.php';
