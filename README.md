Admin template

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]



## Install

Via Composer

``` bash
$ composer require giaphiep/post
```

## Usage
1. In configs/app.php file, add the following to the providers array (optional in version 5.5)
``` php
GiapHiep\Post\PostServiceProvider::class,
Yajra\DataTables\DataTablesServiceProvider::class,

```
and in aliases array
``` php
'DataTables' => Yajra\DataTables\Facades\DataTables::class,
```

2. Run commands to publish the package’s config and assets and database
``` bash
$ php artisan vendor:publish
$ php artisan migrate
```
3. Run commands to clear cache
``` bash
 php artisan route:clear
 php artisan config:clear
```
4. Ensure that the files & images directories (in config/lfm.php) are writable by your web server (run commands like chown or chmod).

5. In app/Http/Kernel.php, Add the following to the routeMiddleware array

``` php
 'optimizeImages' => \Spatie\LaravelImageOptimizer\Middlewares\OptimizeImages::class,
```

6. In config/lfm.php, add the following to the middlewares array

``` php
'middlewares' => [ ..., 'optimizeImages'],
```



## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email hiep.giapvan@gmail.com instead of using the issue tracker.

## Credits

- [Giáp Hiệp][https://giaphiep.com]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/giaphiep/post.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/giaphiep/post/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/:vendor/:package_name.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/:vendor/:package_name.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/giaphiep/post.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/giaphiep/post
[link-travis]: https://travis-ci.org/giaphiep/post
[link-scrutinizer]: https://scrutinizer-ci.com/g/giaphiep/post/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/giaphiep/post
[link-downloads]: https://packagist.org/packages/giaphiep/post
[link-author]: https://github.com/giaphiep
[link-contributors]: ../../contributors
