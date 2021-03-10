<?php

declare(strict_types=1);

namespace Anng\lib\annotations;

use Exception;
use Symfony\Component\Finder\Finder;

class Annotaions
{
    public function __construct()
    {
        $finder = new Finder();
        $finder->in(__DIR__ . '/module');
        foreach ($finder->files() as $value) {
            dump($value);
        }

        try {
            //code...
        } catch (Exception $th) {
            //throw $th;
        }
    }
}
