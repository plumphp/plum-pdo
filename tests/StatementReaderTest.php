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

use Mockery;
use Mockery\Mock;
use PHPUnit_Framework_TestCase;

/**
 * StatementReaderTest
 *
 * @package   Plum\PlumPdo
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2015 Florian Eckerstorfer
 * @group     unit
 */
class StatementReaderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers Plum\PlumPdo\StatementReader::__construct()
     * @covers Plum\PlumPdo\StatementReader::getIterator()
     */
    public function getIteratorReturnsIterator()
    {
        $statement = Mockery::mock('\PDOStatement');
        $statement->shouldReceive('fetchAll')->with(\PDO::FETCH_ASSOC)->once()->andReturn([['id' => 1]]);
        $reader = new StatementReader($statement);

        $iterator = $reader->getIterator();

        $this->assertInstanceOf('\ArrayIterator', $iterator);
        foreach ($iterator as $item) {
            $this->assertEquals(1, $item['id']);
        }
    }
    /**
     * @test
     * @covers Plum\PlumPdo\StatementReader::__construct()
     * @covers Plum\PlumPdo\StatementReader::count()
     */
    public function countReturnsCount()
    {
        $statement = Mockery::mock('\PDOStatement');
        $statement->shouldReceive('fetchAll')->with(\PDO::FETCH_ASSOC)->once()->andReturn([['id' => 1]]);
        $reader = new StatementReader($statement);

        $this->assertEquals(1, $reader->count());
    }
}
