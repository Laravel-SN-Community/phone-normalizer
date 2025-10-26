# Phone Normalizer for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravelsn/phone-normalizer.svg?style=flat-square)](https://packagist.org/packages/laravelsn/phone-normalizer)
[![Total Downloads](https://img.shields.io/packagist/dt/laravelsn/phone-normalizer.svg?style=flat-square)](https://packagist.org/packages/laravelsn/phone-normalizer)

A simple and efficient Laravel package to normalize phone numbers for various countries, with a focus on African nations.

## Features

- ✅ Normalize phone numbers to international format
- ✅ Validate phone number format and length
- ✅ Support for multiple countries (Senegal, Côte d'Ivoire, and more)
- ✅ Easy to extend with new countries
- ✅ Configurable default country
- ✅ Laravel auto-discovery support

## Installation

Install the package via Composer:

```bash
composer require laravelsn/phone-normalizer
```

The service provider will be automatically registered.

### Publish Configuration (Optional)

If you want to customize the configuration:

```bash
php artisan vendor:publish --tag=phonenormalizer-config
```

This will create a `config/phonenormalizer.php` file where you can customize settings.

## Usage

### Basic Usage

```php
use Laravelsn\PhoneNormalizer\Facades\PhoneNormalizer as Phone;

// Normalize a Senegalese phone number
$normalized = Phone::normalize('78 123 45 67');
// Returns: +221781234567

// Normalize with spaces or special characters
$normalized = Phone::normalize('78-123-45-67');
// Returns: +221781234567
```

### Specify Country Code

```php
// Normalize a phone number from Côte d'Ivoire
$normalized = Phone::normalize('0123456789', 'CI');
// Returns: +2250123456789

// Senegal (default)
$normalized = Phone::normalize('771234567', 'SN');
// Returns: +221771234567
```

### Validation

The `normalize()` method returns `null` if the phone number is invalid:

```php
$normalized = Phone::normalize('123'); // Invalid number
// Returns: null

if ($normalized === null) {
    // Handle invalid phone number
}
```

## Configuration

After publishing the configuration file, you can customize the default country and add new countries:

```php
// config/phonenormalizer.php

return [
    'default_country' => env('PHONE_NORMALIZER_DEFAULT_COUNTRY', 'SN'),
    
    'countries' => [
        'SN' => [
            'code' => '+221',
            'pattern' => '/^(7[05678][0-9]{7})$/',
            'length' => 9,
        ],
        'CI' => [
            'code' => '+225',
            'pattern' => '/^(0[157]|2[57])[0-9]{8}$/',
            'length' => 10,
        ],
        // Add more countries here
    ],
];
```

### Environment Variables

Set the default country in your `.env` file:

```env
PHONE_NORMALIZER_DEFAULT_COUNTRY=SN
```

## Supported Countries

| Country | Code | Format | Example |
|---------|------|--------|---------|
| Senegal | SN | 9 digits | 771234567 → +221771234567 |
| Côte d'Ivoire | CI | 10 digits | 0123456789 → +2250123456789 |

Want to add more countries? See the [Contributing](#contributing) section!

## Adding New Countries

You can add new countries by publishing the configuration and adding them to the `countries` array:

```php
'countries' => [
    'ML' => [ // Mali
        'code' => '+223',
        'pattern' => '/^[0-9]{8}$/',
        'length' => 8,
    ],
    // ... other countries
],
```

## Use Cases

- **User Registration**: Normalize phone numbers during registration
- **SMS Sending**: Ensure phone numbers are in the correct format before sending SMS
- **Database Storage**: Store phone numbers in a consistent format
- **API Integration**: Normalize phone numbers before sending to external APIs

## Example in Controller

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravelsn\PhoneNormalizer\Facades\PhoneNormalizer as Phone;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
        ]);

        $normalizedPhone = Phone::normalize($validated['phone']);

        if ($normalizedPhone === null) {
            return back()->withErrors([
                'phone' => 'Invalid phone number format'
            ]);
        }

        User::create([
            'name' => $validated['name'],
            'phone' => $normalizedPhone,
        ]);

        return redirect()->route('users.index');
    }
}
```

## Testing

Run the tests with:

```bash
composer test
```

Or using Pest directly:

```bash
./vendor/bin/pest
```

## Requirements

- PHP 8.3 or higher
- Laravel 11.0 or 12.0

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

### How to Contribute

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Adding New Countries

We especially welcome contributions to add support for new countries! Just add the country configuration following the existing pattern.

## Security

If you discover any security-related issues, please email laravelsenegal@gmail.com instead of using the issue tracker.

## Credits

- [LaravelSn](https://github.com/laravelsn)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Changelog

### 1.0.0 - 2025-10-26

- Initial release
- Support for Senegal (SN)
- Support for Côte d'Ivoire (CI)
- Phone number validation
- Phone number normalization to international format

## Support

If you find this package helpful, please consider:
- ⭐ Starring the repository
- 🐛 Reporting bugs
- 💡 Suggesting new features
- 📖 Improving documentation

---

Made with ❤️ by [LaravelSn](https://github.com/laravelsn)

