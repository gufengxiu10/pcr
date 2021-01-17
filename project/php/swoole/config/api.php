<?php

return [
    'hcyacg_token' => env("HCYACG_TOKEN"),
    'pixiv_biu' => [
        'host'  => '172.200.1.5',
        'prot'  => 80,
        'api'   => [
            'info'  => [
                'rank'  => 'api/biu/get/rank'
            ]
        ]
    ]
];
