# Token Helper

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Travis](https://img.shields.io/travis/tumainimosha/token-helper.svg?style=flat-square)]()
[![Total Downloads](https://img.shields.io/packagist/dt/tumainimosha/token-helper.svg?style=flat-square)](https://packagist.org/packages/tumainimosha/token-helper)

## Install
`composer require tumainimosha/token-helper`

## Usage
```php
use Tumainimosha\TokenHelper\TokenHelper;

// Generating jwt token. jwt is base64 encoded string
$jwt_string = (new TokenHelper)->getToken([
        'terminal_id' => $terminal->id,
        'token_id' => $tokenModel->id,
    ], 60 * 24 * 365 * 5);

     
// validating jwt token
$helper = new TokenHelper;
$status = $helper->validateToken($jwt_string);

if ($status === $helper::OK) {
   //handle success case
}

elseif ($status === $helper::EXPIRED) {
    //handle success case
}

else {
    // handle invalid token
}

```

## Testing
@TODO

``` bash
vendor/bin/phpunit
```

## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security
If you discover any security-related issues, please email princeton.mosha@gmail.com instead of using the issue tracker.

## License
The MIT License (MIT). Please see [License File](/LICENSE.md) for more information.