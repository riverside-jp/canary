# canary

Toggle the features between current version to canary version.

## Example

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use RiversideHotel\Canary\FeatureToggle;
use RiversideHotel\Canary\Partitioning\Strategy;

$config = [
    [
        'name' => 'new feature',
        'enabled'  => true,
        'strategy' => [
            'type' => Strategy::TYPE_PERCENTAGE,
            'args' => [10], // rolls out this features to 10% users
        ],
    ],
];

$toggle = FeatureToggle::createWithConfiguration($config);

// If the feature is not explicitly defined,
// this value will be returned.
$default = false;

// context is the scalar value used to check if the feature is available.
// The value can be a user ID, content ID, or something else.
$context = 1;

if ($toggle->isAvailable('new feature', $default, $context)) {
    echo 'new feature is available';
} else {
    echo 'new feature is unavailable';
}
```

## Usage

WIP (Please see [Test Case](tests) for the time being)

## License

canary is licensed under the [MIT License](LICENSE).