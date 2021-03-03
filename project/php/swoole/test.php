<?php


require_once "vendor/autoload.php";

#[Attribute]
class ico
{
    public $value;

    public function __construct($value)
    {
        $this->value = $value;
    }
}

class test
{
    #[ico(100)]
    public function run($b)
    {
        dump(100);
    }
}



$reflection = new ReflectionClass('test');

$arr = [];

$class = $reflection->getAttributes();
foreach ($reflection->getMethods() as $method) {
    $attributes = $method->getAttributes(ico::class);
    foreach ($attributes as $attribute) {
        $listener = $attribute->newInstance();
        $arr[] = [$listener->value, ['test', $method->getName()]];
    }
}


dump($arr);
