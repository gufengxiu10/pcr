<?php

use Anng\lib\Config;
use Anng\lib\Container;
use Anng\lib\Env;
use Anng\lib\Facade;
use Symfony\Component\Finder\Finder;

require_once "vendor/autoload.php";


dump(Swoole\Timer::stats());
// $finder = new Finder();

// $finder->files()->in(__DIR__ . '/config');

// foreach ($finder as $file) {
//     $absoluteFilePath = $file->getRealPath();
//     $data = include($absoluteFilePath);
// }

// $container = new Container();
// // $container->make(Config::class);
// // $container->make(Env::class);

// $container->bind([
//     'Env' => Env::class,
//     'Facade' => Facade::class
// ]);

// dump($container->has(Facade::class));
// dump($container->get(Env::class));
// dump($container->get(Env::class));
// dump($container->get(Env::class));
// dump($container->get(Env::class));

// dump($container->make('Env'));
// dump($container->make('Env'));
