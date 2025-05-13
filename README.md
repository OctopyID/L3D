# L3D (Laravel Domain-Driven Design)

Welcome to L3D – an elegant and modular solution for structuring your Laravel applications using Domain-Driven Design (DDD) principles. 🚀

## Why Choose L3D?

L3D helps you:

- 📂 Organize your code in a modular, scalable way
- 🎯 Separate business logic into well-defined domains
- ⚡ Enhance application maintainability and scalability
- 🔍 Make code easier to navigate and understand
- 🛠️ Embrace clean architecture principles with ease

Automated Magic! 🎉
With L3D, you don’t need to manually:

- ✅ Register service providers
- ✅ Configure migration paths
- ✅ Set up domain-specific routes
- ✅ Define factory locations

and more...

All of this is automatically handled behind the scenes. 🪄

## Compatibility

- PHP 8.4+
- Laravel 12.0+

## Installation

### 1. Install via Composer:

```shell script
composer require octopy/l3d
```

### 2. Register domain paths manually (Optional)

By default, L3D will automatically discover domains inside the default directory.
If you prefer to store your domains elsewhere, you can configure the location like this:

```php
public function register()
{
    l3d()->register([
        'App\\Domain\\' => app_path('Domain')
    ]);
}
```

## Production Optimization

For maximum performance in production, enable domain caching:

```shell script
php artisan l3d:cache
```

Need to clear the cache? Use:

```shell script
php artisan l3d:clear
```

## Contributing

We greatly appreciate contributions from the community! Feel free to submit pull requests or report issues if you find bugs or have suggestions for improvements.

## Security

If you discover any security related issues, please email [bug@octopy.dev](mailto:bug@octopy.dev) instead of using the issue
tracker.

## Credits

- [Supian M](https://github.com/SupianIDz)
- [Octopy ID](https://github.com/OctopyID)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
