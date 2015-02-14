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
use PDO;
use PDOStatement;
use Plum\Plum\Reader\ReaderInterface;
use Traversable;

/**
 * StatementReader
 *
 * @package   Plum\PlumPdo
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2015 Florian Eckerstorfer
 */
class StatementReader implements ReaderInterface
{
    /**
     * @var PDOStatement
     */
    private $statement;

    /**
     * @var int
     */
    private $fetchStyle;

    /**
     * @var ArrayIterator
     */
    private $iterator;

    /**
     * @param PDOStatement $statement
     * @param int          $fetchStyle
     */
    public function __construct(PDOStatement $statement, $fetchStyle = PDO::FETCH_ASSOC)
    {
        $this->statement  = $statement;
        $this->fetchStyle = $fetchStyle;
    }

    /**
     * @return Traversable
     */
    public function getIterator()
    {
        if (!$this->iterator) {
            $this->iterator = new ArrayIterator($this->statement->fetchAll($this->fetchStyle));
        }

        return $this->iterator;
    }

    /**
     * @return int
     */
    public function count()
    {
        $this->getIterator();

        return $this->iterator->count();
    }
}
