<?php

declare(strict_types=1);

namespace Anng\lib;

use Attribute;
use ReflectionAttribute;
use ReflectionClass;

class Annotations
{

    private $instances = [];

    public function set(array $attribut)
    {
        dump($attribut);
    }
}
