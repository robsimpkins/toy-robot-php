<?php

namespace Test;

use ToyRobot\Board;

class BoardTest extends TestCase
{
    /**
     * \ToyRobot\Board  Board instance.
     */
    protected $board;


    /**
     * Set up test case.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->board = new Board(5, 5);
    }

    /**
     * Test coordinates that are within board bounds.
     *
     * @covers \ToyRobot\Board::withinBounds
     * @return void
     */
    public function testCoordinatesWithinBounds()
    {
        $this->assertTrue($this->board->withinBounds(0, 0));
        $this->assertTrue($this->board->withinBounds(2, 2));
        $this->assertTrue($this->board->withinBounds(0, 4));
        $this->assertTrue($this->board->withinBounds(4, 0));
        $this->assertTrue($this->board->withinBounds(4, 4));
    }

    /**
     * Test coordinates that are outside board bounds.
     *
     * @covers \ToyRobot\Board::withinBounds
     * @return void
     */
    public function testCoordinatesOutsideBounds()
    {
        $this->assertFalse($this->board->withinBounds(0, -1));
        $this->assertFalse($this->board->withinBounds(-1, -1));
        $this->assertFalse($this->board->withinBounds(5, 0));
        $this->assertFalse($this->board->withinBounds(5, 5));
    }
}
