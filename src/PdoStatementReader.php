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

use PDO;
use PDOStatement;
use Plum\Plum\Reader\ReaderInterface;

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
    private $fetchStyle;

    /**
     * @var array
     */
    private $rows;

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
     * @return \Traversable
     */
    public function getIterator()
    {
        if ($this->rows) {
            return $this->getArrayIterator();
        }

        return $this->getFetchIterator();
    }

    /**
     * @return int
     */
    public function count()
    {
        if (!$this->rows) {
            $this->rows = $this->statement->fetchAll($this->fetchStyle);
        }

        return count($this->rows);
    }

    /**
     * @return \Generator
     */
    protected function getArrayIterator()
    {
        foreach ($this->rows as $row) {
            yield $row;
        }
    }

    /**
     * @return \Generator
     */
    protected function getFetchIterator()
    {
        $this->rows = [];
        while ($row = $this->statement->fetch($this->fetchStyle)) {
            $this->rows[] = $row;
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
