<?php

use App\Controller\Group\Price;

return [
    [
        'class' => Price::class,
        'method' => 'sendImg',
        'key' => ['来一图'],
        'action' => 'send_group_msg',
        'type' => 'image'
    ]
];
