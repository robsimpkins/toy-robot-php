<?php

namespace Test;

use InvalidArgumentException;
use ToyRobot\Board;
use ToyRobot\Robot;

class RobotTest extends TestCase
{
    /**
     * @var \Mockery\MockInterface  Board mock.
     */
    protected $board;

    /**
     * @var \ToyRobot\Robot  Robot instance.
     */
    protected $robot;



    /**
     * Set up test case.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->board = $this->mock(Board::class, [5, 5]);
        $this->robot = $this->mock(Robot::class, [$this->board]);
    }

    /**
     * Test valid commands are parsed and executed.
     *
     * @covers \ToyRobot\Robot::execute
     * @return void
     */
    public function testValidCommandsAreExecuted()
    {
        $this->robot->shouldReceive('place')->once()->with(1, 2, Robot::DIRECTION_NORTH);
        $this->robot->execute('PLACE 1,2,NORTH');

        $this->robot->shouldReceive('move')->once();
        $this->robot->execute('MOVE');

        $this->robot->shouldReceive('rotate')->once()->with(Robot::ROTATION_LEFT);
        $this->robot->execute('LEFT');

        $this->robot->shouldReceive('rotate')->once()->with(Robot::ROTATION_RIGHT);
        $this->robot->execute('RIGHT');

        $this->setOutputCallback(function() {});
        $this->robot->shouldReceive('report')->once()->andReturn('1,3,NORTH');
        $this->robot->execute('REPORT');
    }

    /**
     * Test valid place commands are executed.
     *
     * @covers \ToyRobot\Robot::place
     * @return void
     */
    public function testValidPlaceCommandsAreExecuted()
    {
        $this->robot->place(4, 4, Robot::DIRECTION_SOUTH);
        $this->assertEquals($this->robot->report(), '4,4,' . Robot::DIRECTION_SOUTH);
    }

    /**
     * Test invalid place commands throw exception.
     *
     * @covers \ToyRobot\Robot::place
     * @return void
     */
    public function testInvalidPlaceCommandsThrowException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->robot->place(5, 5, Robot::DIRECTION_NORTH);

        $this->expectException(InvalidArgumentException::class);
        $this->robot->place(0, 0, 'RANDOM');
    }

    /**
     * Test valid move commands are executed.
     *
     * @covers \ToyRobot\Robot::move
     * @return void
     */
    public function testValidMoveCommandsAreExecuted()
    {
        $this->robot->place(2, 2, Robot::DIRECTION_NORTH);
        $this->robot->move();
        $this->assertEquals($this->robot->report(), '2,3,' . Robot::DIRECTION_NORTH);

        $this->robot->place(2, 2, Robot::DIRECTION_EAST);
        $this->robot->move();
        $this->assertEquals($this->robot->report(), '3,2,' . Robot::DIRECTION_EAST);

        $this->robot->place(2, 2, Robot::DIRECTION_SOUTH);
        $this->robot->move();
        $this->assertEquals($this->robot->report(), '2,1,' . Robot::DIRECTION_SOUTH);

        $this->robot->place(2, 2, Robot::DIRECTION_WEST);
        $this->robot->move();
        $this->assertEquals($this->robot->report(), '1,2,' . Robot::DIRECTION_WEST);
    }

    /**
     * Test invalid move commands are ignored.
     *
     * @covers \ToyRobot\Robot::move
     * @return void
     */
    public function testInvalidMoveCommandsAreIgnored()
    {
        $this->robot->place(2, 4, Robot::DIRECTION_NORTH);
        $this->robot->move();
        $this->assertEquals($this->robot->report(), '2,4,' . Robot::DIRECTION_NORTH);

        $this->robot->place(4, 2, Robot::DIRECTION_EAST);
        $this->robot->move();
        $this->assertEquals($this->robot->report(), '4,2,' . Robot::DIRECTION_EAST);

        $this->robot->place(2, 0, Robot::DIRECTION_SOUTH);
        $this->robot->move();
        $this->assertEquals($this->robot->report(), '2,0,' . Robot::DIRECTION_SOUTH);

        $this->robot->place(0, 2, Robot::DIRECTION_WEST);
        $this->robot->move();
        $this->assertEquals($this->robot->report(), '0,2,' . Robot::DIRECTION_WEST);
    }

    /**
     * Test valid rotate commands are executed.
     *
     * @covers \ToyRobot\Robot::rotate
     * @return void
     */
    public function testValidRotateCommandsAreExecuted()
    {
        $this->robot->place(2, 2, Robot::DIRECTION_NORTH);
        $this->robot->rotate(Robot::ROTATION_LEFT);
        $this->assertEquals($this->robot->report(), '2,2,' . Robot::DIRECTION_WEST);

        $this->robot->place(2, 2, Robot::DIRECTION_NORTH);
        $this->robot->rotate(Robot::ROTATION_RIGHT);
        $this->assertEquals($this->robot->report(), '2,2,' . Robot::DIRECTION_EAST);

        $this->robot->place(2, 2, Robot::DIRECTION_EAST);
        $this->robot->rotate(Robot::ROTATION_LEFT);
        $this->assertEquals($this->robot->report(), '2,2,' . Robot::DIRECTION_NORTH);

        $this->robot->place(2, 2, Robot::DIRECTION_EAST);
        $this->robot->rotate(Robot::ROTATION_RIGHT);
        $this->assertEquals($this->robot->report(), '2,2,' . Robot::DIRECTION_SOUTH);

        $this->robot->place(2, 2, Robot::DIRECTION_SOUTH);
        $this->robot->rotate(Robot::ROTATION_LEFT);
        $this->assertEquals($this->robot->report(), '2,2,' . Robot::DIRECTION_EAST);

        $this->robot->place(2, 2, Robot::DIRECTION_SOUTH);
        $this->robot->rotate(Robot::ROTATION_RIGHT);
        $this->assertEquals($this->robot->report(), '2,2,' . Robot::DIRECTION_WEST);

        $this->robot->place(2, 2, Robot::DIRECTION_WEST);
        $this->robot->rotate(Robot::ROTATION_LEFT);
        $this->assertEquals($this->robot->report(), '2,2,' . Robot::DIRECTION_SOUTH);

        $this->robot->place(2, 2, Robot::DIRECTION_WEST);
        $this->robot->rotate(Robot::ROTATION_RIGHT);
        $this->assertEquals($this->robot->report(), '2,2,' . Robot::DIRECTION_NORTH);
    }

    /**
     * Test invalid rotate commands throw exception.
     *
     * @covers \ToyRobot\Robot::rotate
     * @return void
     */
    public function testInvalidRotateCommandsThrowException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->robot->place(2, 2, Robot::DIRECTION_NORTH);
        $this->robot->rotate('UP');

        $this->expectException(InvalidArgumentException::class);
        $this->robot->place(2, 2, Robot::DIRECTION_NORTH);
        $this->robot->rotate('DOWN');
    }

    /**
     * Test report commands are executed.
     *
     * @covers \ToyRobot\Robot::report
     * @return void
     */
    public function testReportCommandsAreExecuted()
    {
        $this->robot->place(0, 0, Robot::DIRECTION_NORTH);
        $this->assertEquals($this->robot->report(), '0,0,' . Robot::DIRECTION_NORTH);

        $this->robot->place(0, 4, Robot::DIRECTION_EAST);
        $this->assertEquals($this->robot->report(), '0,4,' . Robot::DIRECTION_EAST);

        $this->robot->place(4, 4, Robot::DIRECTION_SOUTH);
        $this->assertEquals($this->robot->report(), '4,4,' . Robot::DIRECTION_SOUTH);

        $this->robot->place(4, 0, Robot::DIRECTION_WEST);
        $this->assertEquals($this->robot->report(), '4,0,' . Robot::DIRECTION_WEST);
    }
}
