<?php

declare(strict_types=1);

namespace Anng\lib\annotations\module;

use Anng\lib\annotations\AnnotationsContract;
use Anng\lib\facade\Annotations as FacadeAnnotations;
use Attribute;

#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
class Cq
{
    public function __construct()
    {
        # code...
    }

    public function param()
    {
    }
}
