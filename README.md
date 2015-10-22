<h1 align="center">
    <img src="http://cdn.florian.ec/plum-logo.svg" alt="Plum" width="300">
</h1>

> PlumPdo integrates [PDO](http://php.net/manual/en/book.pdo.php) into Plum. Plum is a data processing pipeline for PHP.

[![Build Status](https://travis-ci.org/plumphp/plum-pdo.svg)](https://travis-ci.org/plumphp/plum-pdo)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/plumphp/plum-pdo/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/plumphp/plum-pdo/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/plumphp/plum-pdo/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/plumphp/plum-pdo/?branch=master)

Developed by [Florian Eckerstorfer](https://florian.ec) in Vienna, Europe.


Installation
------------

You can install Plum using [Composer](http://getcomposer.org).

```shell
$ composer require plumphp/plum-pdo
```


Usage
-----

Please refer to the [Plum documentation](https://github.com/plumphp/plum/blob/master/docs/index.md) for more
information.

Currently PlumPdo contains `PdoStatementReader` to read data from a PDO-compatible database.

### `PdoStatementReader`

`Plum\PlumPdo\PdoStatementReader` returns an iterator for the result set of a `PDOStatement`. The `execute()` method
has to be called before.

```php
use Plum\PlumPdo\PdoStatementReader;

$statement = $pdo->prepare('SELECT * FROM users WHERE age >= :min_age');
$statement->bindValue(':min_age', 18);
$statement->execute();

$reader = new PdoStatementReader($statement);
$reader->getIterator(); // -> \ArrayIterator
$reader->count();
```

The default behavior shown in the example above is that `getIterator()` will call `fetchAll()` on the `PDOStatement`
and returns the result in the form of an `\ArrayIterator`. However, if the result set is very large and memory becomes
a concern it is possible to fetch the result set row by row and yield each row to the workflow. You can invoke the
behaviour by setting the option `yield` to `true`.

In the following example `getIterator()` returns a `\Generator`.

```php
use Plum\PlumPdo\PdoStatementReader;

$statement = $pdo->prepare('SELECT * FROM users WHERE age >= :min_age');
$statement->bindValue(':min_age', 18);
$statement->execute();

$reader = new PdoStatementReader($statement, [’yield' => true]);
$iterator = $reader->getIterator(); // -> \Generator
foreach ($iterator as $row) {
}
```

The downside of using `yield` is that the reader is no longer countable and when invoking `count()` on such a reader
a `\LogicException` will be thrown.


Change Log
----------

### Version 0.1.1 (6 October 2015)

- Fix Plum version

### Version 0.1 (22 April 2015)

- Initial release


License
-------

The MIT license applies to plumphp/plum-pdo. For the full copyright and license information,
please view the LICENSE file distributed with this source code.
