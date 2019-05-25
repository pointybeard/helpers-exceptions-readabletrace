# PHP Helpers: Readable Trace Exception

-   Version: v1.0.0
-   Date: May 25 2019
-   [Release notes](https://github.com/pointybeard/helpers-exceptions-readabletrace/blob/master/CHANGELOG.md)
-   [GitHub repository](https://github.com/pointybeard/helpers-exceptions-readabletrace)

Provides an exception base class that adds a plain-text, readable, backtrace to the end of the exception message.

## Installation

This library is installed via [Composer](http://getcomposer.org/). To install, use `composer require pointybeard/helpers-exceptions-readabletrace` or add `"pointybeard/helpers-exceptions-readabletrace": "~1.0"` to your `composer.json` file.

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

### Requirements

This library makes use of the [PHP Helpers: Path Functions](https://github.com/pointybeard/helpers-functions-paths) (`pointybeard/helpers-functions-paths`). It is installed automatically via composer.

To include all the [PHP Helpers](https://github.com/pointybeard/helpers) packages on your project, use `composer require pointybeard/helpers` or add `"pointybeard/helpers": "~1.1"` to your composer file.

## Usage

Here is an example of how to use the `ReadableTraceException` base class:

```php
<?php

declare(strict_types=1);
include __DIR__.'/vendor/autoload.php';
use pointybeard\Helpers\Exceptions\ReadableTrace\ReadableTraceException;

class foo
{
    public function __construct()
    {
        // Go a little deeper so there is more to show in the backtrace
        $this->someMethod();
    }

    private function someMethod()
    {
        // Do some work. Trigger an error.
        throw new ReadableTraceException('Oh oh, something went wrong.');
    }
}

try {
    // Do some work here
    $f = new Foo();
} catch (ReadableTraceException $ex) {
    /*
     * Trace is automatically included when
     * ReadableTraceException::getMessage() is called
     **/
    echo $ex->getMessage();
    // Oh oh, something went wrong. See the trace below for more details...
    //
    // Trace
    // ==========
    // [test.php:12] foo->throwException();
    // [test.php:24] foo->__construct();

    /*
     * Access the trace seperately with
     * ReadableTraceException::getReadableTrace()
     */
    var_dump($ex->getReadableTrace());
    // string(70) "[test.php:12] foo->throwException();
    // [test.php:24] foo->__construct();"
}

```

## Support

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/pointybeard/helpers-exceptions-readabletrace/issues),
or better yet, fork the library and submit a pull request.

## Contributing

We encourage you to contribute to this project. Please check out the [Contributing documentation](https://github.com/pointybeard/helpers-exceptions-readabletrace/blob/master/CONTRIBUTING.md) for guidelines about how to get involved.

## License

"PHP Helpers: Readable Trace Exception" is released under the [MIT License](http://www.opensource.org/licenses/MIT).
