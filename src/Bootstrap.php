<?php

use Ntch\Framework\WebRestful\Routing\Base;

print "<pre>";

// Psr-4
require __DIR__ . '/../vendor/autoload.php';

// Routing
$router = new Base();
$router->routerBase();

die('【ERROR】Router cannot find a matching path.');

