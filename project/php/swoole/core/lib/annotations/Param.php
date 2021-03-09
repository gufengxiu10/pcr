<?php

declare(strict_types=1);

namespace Anng\lib\annotations;

use Anng\lib\Annotations;
use Anng\lib\contract\AnnotationsContract;

class Param extends Annotations implements AnnotationsContract
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
