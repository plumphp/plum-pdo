<?php

/**
 * This file is part of plumphp/plum-pdo.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plum\PlumPdo;

use ArrayIterator;
use LogicException;
use PDO;
use PDOStatement;
use Plum\Plum\Reader\ReaderInterface;
use RuntimeException;

/**
 * PdoStatementReader
 *
 * @package   Plum\PlumPdo
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2015 Florian Eckerstorfer
 */
class PdoStatementReader implements ReaderInterface
{
    /**
     * @var PDOStatement
     */
    private $statement;

    /**
     * @var int
     */
    private $options = [
        'fetchStyle' => PDO::FETCH_ASSOC,
        'yield'      => false,
    ];

    /**
     * @var ArrayIterator
     */
    private $iterator;

    /**
     * @param PDOStatement $statement
     * @param array        $options
     */
    public function __construct(PDOStatement $statement, array $options = [])
    {
        $this->statement = $statement;
        $this->options   = array_merge($this->options, $options);
    }

    /**
     * @return \Traversable
     */
    public function getIterator()
    {
        return $this->options['yield'] ?
            $this->getYieldIterator() :
            $this->getArrayIterator();
    }

    /**
     * @return int
     *
     * @throws RuntimeException if the `yield` option is set to `true`.
     */
    public function count()
    {
        if ($this->options['yield']) {
            throw new RuntimeException('Could not count \PDOStatement because "yield" option is set to true. If '.
                                     'the reader should be countable please set the option "yield" to false.');
        }

        return count($this->getArrayIterator());
    }

    /**
     * @return ArrayIterator
     */
    protected function getArrayIterator()
    {
        return $this->iterator ?
            $this->iterator :
            $this->iterator = new ArrayIterator($this->statement->fetchAll($this->options['fetchStyle']));
    }

    /**
     * @return \Generator
     */
    protected function getYieldIterator()
    {
        while ($row = $this->statement->fetch($this->options['fetchStyle'])) {
            yield $row;
        }
    }

    /**
     * @param mixed $input
     *
     * @return bool
     */
    public static function accepts($input)
    {
        return $input instanceof PDOStatement;
    }
}
