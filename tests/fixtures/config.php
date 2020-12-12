<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use RiversideHotel\Canary\Partitioning\Strategy;

return [
    [
        'name' => 'foo',
        'enabled'  => true,
        'strategy' => [
            'type' => Strategy::TYPE_ALLOWLIST,
            'args' => [
                ['foo1', 'foo2', 'foo3'],
            ],
        ],
    ],
    [
        'name' => 'bar',
        'enabled'  => true,
        'strategy' => [
            'type' => Strategy::TYPE_DATE_RANGE,
            'args' => [
                new DateTime('2020-01-01 00:00:00'),
                new DateTime('2021-01-01 00:00:00'),
            ],
        ],
    ],
    [
        'name' => 'baz',
        'enabled'  => true,
        'strategy' => [
            'type' => Strategy::TYPE_DENYLIST,
            'args' => [
                ['baz1', 'baz2', 'baz3'],
            ],
        ],
    ],
    [
        'name' => 'qux',
        'enabled' => true,
        'strategy' => [
            'type' => Strategy::TYPE_PERCENTAGE,
            'args' => [100],
        ]
    ],
    [
        'name' => 'quux',
        'enabled' => false,
    ],
];
