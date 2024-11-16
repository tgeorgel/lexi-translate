# Lexi Translate

[![Latest Version on Packagist](https://img.shields.io/packagist/v/omaralalwi/lexi-translate.svg?style=flat-square)](https://packagist.org/packages/omaralalwi/lexi-translate)
[![Total Downloads](https://img.shields.io/packagist/dt/omaralalwi/lexi-translate.svg?style=flat-square)](https://packagist.org/packages/omaralalwi/lexi-translate)

<p align="center">
  <a href="https://omaralalwi.github.io/lexi-translate" target="_blank">
    <img src="https://raw.githubusercontent.com/omaralalwi/lexi-translate/master/public/images/lexi-translate-banner.jpg" alt="lexi translate banner">
  </a>
</p>

simplify managing translations for multilingual Eloquent models with power of **morph relationships** and **caching** .

Its lightweight design and flexibility make it an excellent choice for applications needing multi-language support with minimal performance overhead.

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
  - [Defining LexiTranslatable Models](#defining-lexitranslatable-models)
  - [Create Translations](#Create-translations)
  - [Retrieving Translations](#retrieving-translations)
  - [Eager Loading Translations](#eager-loading-translations)
  - [Cache Handling](#cache-handling)
- [Testing](#testing)
- [Changelog](#changelog)
- [Contributing](#contributing)
- [Features](#features)
- [Security](#security)
- [License](#license)

## Installation

You can install the package via Composer:

```bash
composer require omaralalwi/lexi-translate
```

### Publishing Configuration File

```bash
php artisan vendor:publish --provider="Omaralalwi\LexiTranslate\Providers\LexiTranslateServiceProvider" --tag=config
```

### Migration for `translations` Table

Run the following command to create the `translations` table:

```bash
php artisan migrate
```

## Usage

### Defining LexiTranslatable Models

To use the package, include the `LexiTranslatable` trait in your Eloquent models, and add translated attributes in `$translatableFields` array:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Omaralalwi\LexiTranslate\Traits\LexiTranslatable;

class Post extends Model
{
    use LexiTranslatable;

    protected $translatableFields = ['title', 'description'];
}
```

### Create Translations

Easily store translations for your model using the built-in relationship:

```php
$post = Post::find(1);

// Store a translation for the title in Arabic
$post->translations()->create([
    'locale' => 'ar',
    'column' => 'title',
    'text' => 'العنوان بالعربية',
]);

// Store a translation for the title in English
$post->translations()->create([
    'locale' => 'en',
    'column' => 'title',
    'text' => 'English Title',
]);

```

### Retrieving Translations

To retrieve translations, simply use the `translate` method:

```php
$title = $post->transAttr('title', 'ar');
```
Or
```php
$titleInArabic = $post->translate('title', 'ar');
$titleInEnglish = $post->translate('title', 'en');
```

### Eager Loading Translations

You can eager load the translations relationship when querying your models to reduce the number of queries:

```php
$posts = Post::with('translations')->get();

// Access translations directly after eager loading
foreach ($posts as $post) {
    $titleInArabic = $post->translate('title', 'ar');
    $titleInEnglish = $post->translate('title', 'en');
}
```

### Cache Handling

**Disable Cache**:

by default the cache enabled, you can disable it  by make `use_cache` = false , in `config/lexi-translate.php` file

**Cache Management**:

Lexi Translate automatically caches translations to boost performance.
Also Cache is cleared automatically when translations are updated or deleted by `booted` function in `Translation` model .

**Clear Model Cache Manually**:

If you need to manually clear the cache, you can do so `$model->clearTranslationsCache()` for ex :

```php
$post->clearTranslationsCache();
```

---
**Note**:

Please note that the `supported_locales` setting in the configuration file defines the locales that will be handled by the cache by default. 
If you add additional locales for translations, make sure to include them in the `supported_locales` list to ensure proper cache handling. Failing to do so may result in cache issues for locales not added to the list.

---

## Features

- **Dynamic Morph Relationships:** Manage translations across different models with ease, thanks to its dynamic morphable relationships.
- **Automatic Caching:** Enjoy enhanced performance as translations are automatically cached and invalidated, ensuring quick access and updates.
- **Fallback Mechanism:** Never worry about missing translations—Lexi Translate falls back to the default language if a translation is not available.
- **Simple, Intuitive API:** A clean and consistent API for adding, retrieving, and managing translations.
- **Eloquent-Friendly:** Seamlessly integrates with Laravel's Eloquent ORM, making it easy to work with translated data while maintaining the power of Laravel’s query builder.
- **Feature Tests:** supported with Feature Tests .

## Testing

To run the tests for this package:

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on recent updates.

## Contributing

We welcome contributions! If you'd like to contribute, please check the [CONTRIBUTING](CONTRIBUTING.md) guide for details.

### Contributors

This project exists thanks to all the people who contribute.

- [Omar alalwi](https://github.com/omaralalwi)

## Security

If you discover any security-related issues, please email [omaralwi2010@gmail.com](mailto:omaralwi2010@gmail.com) instead of using the issue tracker.

## License

The MIT License (MIT). Please see the [License File](LICENSE.md) for more information.

