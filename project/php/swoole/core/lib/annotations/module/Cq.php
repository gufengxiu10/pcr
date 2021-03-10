<?php

declare(strict_types=1);

namespace Anng\lib\annotations\module;

use Anng\lib\annotations\AnnotationsContract;
use Anng\lib\facade\Annotations as FacadeAnnotations;
use Attribute;

#[Attribute]
class Cq implements AnnotationsContract
{
    public function __construct($key, $alias = [])
    {
        FacadeAnnotations::module(static::class);
    }

    public function param()
    {
    }
}
