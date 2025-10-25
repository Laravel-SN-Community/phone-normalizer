# 🚀 Simple Notes Package - Hacktoberfest Demo
## GalSenDev Community Presentation

> **Duration:** ~45 minutes | **Audience:** PHP/Laravel developers | **Goal:** Build a complete Laravel package from scratch

---

## 📋 Presentation Overview

### What We're Building
A complete Laravel package called "Simple Notes" that provides a clean API for managing notes in Laravel applications.

### Learning Objectives
- ✅ Understanding Laravel package architecture
- ✅ Service providers and facades
- ✅ Database migrations and models
- ✅ Testing with Pest and Orchestra Testbench
- ✅ Code quality with Laravel Pint
- ✅ Publishing packages to Packagist

---

## 🎯 Demo Steps

### 1. 📁 Project Setup
**Time: 5 minutes**

Create a new directory and initialize the package:
```bash
mkdir phone-normalizer
cd phone-normalizer
composer init
```

**Key points to mention:**
- Package naming conventions
- Composer.json structure
- PSR-4 autoloading

### 2. 🔧 Essential Configuration Files
**Time: 5 minutes**

Add these crucial files for a professional package:

#### `.gitignore`
```gitignore
/vendor/
.env
.phpunit.result.cache
composer.lock
```

#### `.gitattributes`
```gitattributes
* text=auto
*.php diff=php
*.md text
```

#### `.editorconfig`
```ini
root = true

[*]
charset = utf-8
end_of_line = lf
indent_style = space
indent_size = 4
insert_final_newline = true
trim_trailing_whitespace = true
```

### 4. 🏗️ Package Structure
**Time: 10 minutes**

Create the core package files:

#### Configuration
- `config/phonenormalizer.php` - Package configuration

#### Core Classes
- `src/Providers/PhoneNormalizerServiceProvider.php` - Service provider
- `src/PhoneNormalizerManager.php` - Core manager class
- `src/Facades/PhoneNormalizer.php` - Facade for easy access


### Config

```php
<?php

return [
    'default_country' => 'SN',
    'countries' => [
        'SN' => [
            'code' => '+221',
            'pattern' => '/^(7[05678][0-9]{7})$/',
            'length' => 9,
        ],
        // Structure prête pour d'autres pays
        'CI' => [
            'code' => '+225',
            'pattern' => '/^(0[157]|2[57])[0-9]{8}$/',
            'length' => 10,
        ],
    ],
];
```


### PhoneNormalizerServiceProvider

```php
<?php

class PhoneNormalizerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('phone.normalizer', function ($app) {
            return new PhoneNormalizerManager();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/phonenormalizer.php' => config_path('phonenormalizer.php'),
        ], 'phonenormalizer-config');
    }
}
```

### PhoneNormalizerManager

```php
<?php

class PhoneNormalizerManager
{
    /**
     * Normalize a phone number for a specific country
     *
     * @param string $phone
     * @param string|null $countryCode
     * @return string|null
     */
    public function normalize(string $phone, ?string $countryCode = null): ?string
    {
        $countryCode = $countryCode ?? Config::get('phonenormalizer.default_country', 'SN');
        
        $countryConfig = Config::get("phonenormalizer.countries.{$countryCode}");
        
        if (!$countryConfig) {
            return null;
        }
        
        // Clean the phone number (remove all non-numeric characters)
        $cleanedPhone = preg_replace('/[^0-9]/', '', $phone);
        
        // Validate the phone number length
        if (strlen($cleanedPhone) !== $countryConfig['length']) {
            return null;
        }
        
        // Validate the phone number pattern
        if (!preg_match($countryConfig['pattern'], $cleanedPhone)) {
            return null;
        }
        
        // Return the normalized phone number with country code
        return $countryConfig['code'] . $cleanedPhone;
    }
}

```

### Facade 

```php
<?php

/**
 * @method static string|null normalize(string $phone, ?string $countryCode = null)
 */
class PhoneNormalizer extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'phone.normalizer';
    }
}

```

### 4. Test 

### PhoneNormalizerManagerTest.php 

```php
<?php

it('normalizes senegalese phone number with spaces', function () {
    $result = Phone::normalize('78 485 28 12');
    
    expect($result)->toBe('+221784852812');
});

it('renders invalid phone number', function () {
    $result = Phone::normalize('78 485 28 12', 'CI');
    
    expect($result)->toBeNull();
});

```

#### Running Tests
```bash
./vendor/bin/pest
./vendor/bin/pint
```

### 5. 🚀 Deployment

#### GitHub Setup
1. Create repository on GitHub
2. Push code with proper commit messages
3. Add README.md with installation instructions

#### Packagist Publishing
1. Submit package to Packagist
2. Enable GitHub integration
3. Set up auto-updates

## 📚 Additional Resources

- [Laravel Package Development Guide](https://laravel.com/docs/packages)
- [Composer Documentation](https://getcomposer.org/doc/)
- [Pest Testing Framework](https://pestphp.com/)
- [Hacktoberfest Guidelines](https://hacktoberfest.com/participation/)


*Happy coding! 🎉 Let's make the PHP community stronger together!* 
