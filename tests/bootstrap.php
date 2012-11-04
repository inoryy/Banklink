<?php

require_once __DIR__.'/autoload.php';

$loader = new ClassLoader();
$loader->add('Banklink', array(__DIR__.'/../src/', __DIR__));
$loader->register();