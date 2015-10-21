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
use PDO;
use PHPUnit_Framework_TestCase;
use stdClass;

/**
 * PdoStatementReaderTest
 *
 * @package   Plum\PlumPdo
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2015 Florian Eckerstorfer
 * @group     unit
 */
class PdoStatementReaderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers Plum\PlumPdo\PdoStatementReader::__construct()
     * @covers Plum\PlumPdo\PdoStatementReader::getIterator()
     * @covers Plum\PlumPdo\PdoStatementReader::getFetchIterator()
     */
    public function getIteratorReturnsIterator()
    {
        /** @var \PDOStatement|\Mockery\MockInterface $statement */
        $statement = Mockery::mock('\PDOStatement');
        $statement->shouldReceive('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->times(3)
            ->andReturn(['id' => 1], ['id' => 2], false);
        $reader = new PdoStatementReader($statement);

        $expected = [
            ['id' => 1],
            ['id' => 2]
        ];
        $i = 0;
        foreach ($reader->getIterator() as $item) {
            $this->assertEquals($expected[$i], $item);
            $i++;
        }
    }
    /**
     * @test
     * @covers Plum\PlumPdo\PdoStatementReader::__construct()
     * @covers Plum\PlumPdo\PdoStatementReader::getIterator()
     * @covers Plum\PlumPdo\PdoStatementReader::getArrayIterator()
     */
    public function getIteratorReturnsArrayIteratorIfCountIsCalledBefore()
    {
        /** @var \PDOStatement|\Mockery\MockInterface $statement */
        $statement = Mockery::mock('\PDOStatement');
        $statement->shouldReceive('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->once()
            ->andReturn([['id' => 1], ['id' => 2]]);
        $reader = new PdoStatementReader($statement);

        $expected = [
            ['id' => 1],
            ['id' => 2]
        ];
        $i = 0;
        $reader->count();
        foreach ($reader->getIterator() as $item) {
            $this->assertEquals($expected[$i], $item);
            $i++;
        }
    }

    /**
     * @test
     * @covers Plum\PlumPdo\PdoStatementReader::__construct()
     * @covers Plum\PlumPdo\PdoStatementReader::count()
     */
    public function countReturnsCount()
    {
        $statement = Mockery::mock('\PDOStatement');
        $statement->shouldReceive('fetchAll')->with(PDO::FETCH_ASSOC)->once()->andReturn([['id' => 1]]);
        $reader = new PdoStatementReader($statement);

        $this->assertEquals(1, $reader->count());
    }

    /**
     * @test
     * @covers Plum\PlumPdo\PdoStatementReader::accepts()
     */
    public function acceptsReturnsTrueIfInputIsPDOStatement()
    {
        $this->assertTrue(PdoStatementReader::accepts(Mockery::mock('\PDOStatement')));
    }

    /**
     * @test
     * @covers Plum\PlumPdo\PdoStatementReader::accepts()
     */
    public function acceptsReturnsFalseIfInputIsNotPDOStatement()
    {
        $this->assertFalse(PdoStatementReader::accepts('foo'));
        $this->assertFalse(PdoStatementReader::accepts([]));
        $this->assertFalse(PdoStatementReader::accepts(new stdClass()));
    }
}
