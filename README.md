# php-code-search

<p align="center">
    <img src="https://static.permafrost.dev/images/php-code-search/php-code-search-logo.png" alt="php-code-search logo" height="200" style="block">
    <br><br>
    <img src="https://img.shields.io/github/v/release/permafrost-dev/php-code-search.svg?sort=semver&logo=github&" alt="Package Version">
    <img src="https://img.shields.io/github/license/permafrost-dev/php-code-search.svg?logo=opensourceinitiative&" alt="license">
    <img src="https://github.com/permafrost-dev/php-code-search/actions/workflows/run-tests.yml/badge.svg?branch=main" alt="Test Run Status">
    <img src="https://codecov.io/gh/permafrost-dev/php-code-search/branch/main/graph/badge.svg" alt="code coverage">
</p>

---

Search PHP source code for function & method calls, variable assignments, and more.

---

## Installation

```bash
composer require permafrost-dev/php-code-search
```

## Searching

To search a file, use the `search` method.  Its only parameter may be either a string containing a valid filename or an instance of `\Permafrost\PhpCodeSearch\Support\File`.

### Function calls

To search for function calls, use the `functions` method before calling `search`.

```php
use Permafrost\PhpCodeSearch\Searcher;

$searcher = new Searcher();

$results = $searcher
    ->functions(['strtolower', 'strtoupper'])
    ->search('./file1.php');
    
foreach($results as $result) {
    echo "Found '{$result->location->name}' on line {$result->location->startLine}" . PHP_EOL;
}
```

### Static method calls

To search for static method calls, use the `static` method before calling `search`.

```php
use Permafrost\PhpCodeSearch\Searcher;

$searcher = new Searcher();

$results = $searcher
    ->static(['Ray', 'Cache'])
    ->search('./app/Http/Controllers/MyController.php');
    
foreach($results as $result) {
    echo "Found '{$result->location->name}' on line {$result->location->startLine}" . PHP_EOL;
}
```

### New class instances

To search for a class created by the `new` keyword, use the `classes` method before calling `search`.

```php
use Permafrost\PhpCodeSearch\Searcher;

$searcher = new Searcher();

$results = $searcher
    ->classes(['MyClass'])
    ->search('./app/Http/Controllers/MyController.php');
    
foreach($results as $result) {
    echo "Found '{$result->location->name}' on line {$result->location->startLine}" . PHP_EOL;
}
```

### Variable assignments

To search for a variable assignment by variable name, use the `assignments` method before calling `search`. _Note: The `$` should be omitted._

```php
use Permafrost\PhpCodeSearch\Searcher;

$searcher = new Searcher();

$results = $searcher
    ->assignments(['myVar'])
    ->search('./app/Http/Controllers/MyController.php');
    
foreach($results as $result) {
    echo "Found '{$result->location->name}' on line {$result->location->startLine}" . PHP_EOL;
}
```

## Testing

```bash
./vendor/bin/phpunit
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Patrick Organ](https://github.com/patinthehat)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
