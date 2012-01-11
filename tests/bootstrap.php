<?php

require_once __DIR__.'/autoload.php';

$loader = new ClassLoader();
$loader->add('Inori\Test', __DIR__);
$loader->add('Inori', __DIR__.'/../src/');
$loader->register();