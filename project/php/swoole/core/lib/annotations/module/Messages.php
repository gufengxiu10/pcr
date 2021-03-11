<?php

declare(strict_types=1);

namespace Anng\lib\annotations\module;

use Anng\lib\annotations\contract\BeforeContract;
use Anng\lib\facade\Messages as FacadeMessages;
use Attribute;

#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
class Messages implements BeforeContract
{
    private $data = [
        'key' => '',
        'alias' => []
    ];

    public function __construct($key)
    {
        $this->data['key'] = $key['key'];
        if (array_key_exists('alias', $key)) {
            $this->data['alias'] = $key['alias'];
        }
    }

    public function run()
    {
        FacadeMessages::set($this->data['key'], $this->method, $this->data['alias']);
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }
}
