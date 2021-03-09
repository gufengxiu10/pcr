<?php

declare(strict_types=1);

namespace Anng\lib\annotations\module;

use Anng\lib\Annotations;
use Anng\lib\contract\AnnotationsContract;
use Attribute;

class Cq extends Annotations implements AnnotationsContract
{
    public function __construct()
    {
        dump(func_get_args());
    }

    public function param()
    {
        # code...
    }
}
