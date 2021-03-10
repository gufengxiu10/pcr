<?php

declare(strict_types=1);

namespace Anng\lib;

use Anng\lib\annotations\module\Cq;
use Attribute;
use ReflectionAttribute;
use ReflectionClass;

class Annotations
{
    private $bind = [
        'cq' => Cq::class
    ];

    private $data = [];

    public function __construct()
    {
        # code...
    }

    public function set(array $attribut)
    {
        dump($attribut);
    }

    public function module($moduel)
    {
        $moduel  = $this->getAlias($moduel);
        if (!array_key_exists($moduel, $this->data)) {
            $this->data[$this->getAlias($moduel)] = [];
        }

        return $this;
    }

    private function getAlias($name)
    {
        if (array_key_exists($name, $this->bind)) {
            return $this->bind[$name];
        }

        return $name;
    }
}
