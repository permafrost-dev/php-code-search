# Searching PHP source code made easy

<p align="center">
    <img src="https://static.permafrost.dev/images/php-code-search/php-code-search-logo.png" alt="php-code-search logo" height="200" style="block">
    <br><br>
    <img src="https://img.shields.io/github/v/release/permafrost-dev/php-code-search.svg?sort=semver&logo=github&" alt="Package Version">
    <img src="https://img.shields.io/github/license/permafrost-dev/php-code-search.svg?logo=opensourceinitiative&" alt="license">
    <img src="https://github.com/permafrost-dev/php-code-search/actions/workflows/run-tests.yml/badge.svg?branch=main" alt="Test Run Status">
    <img src="https://codecov.io/gh/permafrost-dev/php-code-search/branch/main/graph/badge.svg" alt="code coverage">
</p>

---

Search PHP source code for function & method calls, variable assignments, classes and more directly from PHP.

---

## Installation

```bash
composer require permafrost-dev/php-code-search
```

## Searching

To search a file, use the `search` method.  Its only parameter may be either a string containing a valid filename or an instance of `\Permafrost\PhpCodeSearch\Support\File`.

To search a string instead, use the `searchCode` method.

The search methods return an instance of `Permafrost\PhpCodeSearch\Results\FileSearchResults`, which has a `results` property.  

Each `result` is an instance of `Permafrost\PhpCodeSearch\Results\SearchResult` with the following properties:

- `node` - the specific item that was found
  - `node->name(): string`
- `location` - the location in the file that the item was found
  - `location->startLine(): int`
  - `location->endLine(): int`
- `snippet` - a snippet of code lines from the file with the result line in the middle
  - `snippet->toString(): string`
- `file()` _(method)_ - provides access to the file that was searched

### Searching

To search through the code in a string or file, use the `Searcher` class:

```php
use Permafrost\PhpCodeSearch\Searcher;

$searcher = new Searcher();
```

To search a file, use the `search` method, and the `searchCode` method to search a string of code.

```php
$searcher
    ->functions(['strtolower', 'strtoupper'])
    ->search('./file1.php');

$searcher
    ->variables(['/^one[A-Z]$/'])
    ->searchCode('<?php $oneA = "1a";');
```

When searching using any of the available methods, regular expressions can be used by surrounding the name with slashes `/`, i.e. `/test\d+/`.

### Variable names

To search for variables by name, use the `variables` method.

```php
$results = $searcher
    ->variables(['twoA', '/^one.$/'])
    ->searchCode('<?php '.
    '    $oneA = "1a";'.
    '    $oneB = "1b";'.
    '    $twoA = "2a";'.
    '    $twoB = "2b";'.
    '');
    
foreach($results->results as $result) {
    echo "Found '{$result->node->name()}' on line {$result->location->startLine}" . PHP_EOL;
}
```

### Functions

To search for function calls or definitions, use the `functions` method.  

```php
// search for references AND definitions for 'strtolower' and/or 'myfunc'
$searcher
    ->functions(['strtolower', 'myfunc'])
    ->search('file1.php');
```

### Method calls

To search for a method call by name, use the `methods` method.

Method call nodes have an `args` property that can be looped through to retrieve the arguments for the method call.

```php
$results = $searcher
    ->methods(['/test(One|Two)/'])
    ->searchCode('<?php '.
      '    $obj->testOne("hello world 1"); '.
      '    $obj->testTwo("hello world", 2); '.
      ''
    );
    
foreach($results->results as $result) {
    echo "Found '{$result->node->name()}' on line {$result->location->startLine}" . PHP_EOL;

    foreach($result->node->args as $arg) {
        echo "  argument: '{$arg->value}'" . PHP_EOL;
    }
}
```

### Static calls

To search for static method or property calls, use the `static` method.

Valid search terms are either a class name like `Cache`, or a class name and a method name like `Cache::remember`. 

```php
$searcher
    ->static(['Ray', 'Cache::has', 'Request::$myProperty'])
    ->search('./app/Http/Controllers/MyController.php');
```

### Classes

To search for either a class definition or a class created by the `new` keyword, use the `classes` method. 

```php
$searcher
    ->classes(['MyController'])
    ->search('./app/Http/Controllers/MyController.php');
```

### Variable assignments

To search for a variable assignment by variable name, use the `assignments` method. _Note: The `$` should be omitted._

```php
$searcher
    ->assignments(['myVar'])
    ->search('./app/Http/Controllers/MyController.php');
```

### Results without code snippets

To return search results without associated code snippets, use the `withoutSnippets` method:

```php
$searcher
    ->withoutSnippets()
    ->functions(['strtolower'])
    ->search('file1.php');
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
