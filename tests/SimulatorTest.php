<?php

namespace Test;

use ToyRobot\Board;
use ToyRobot\Robot;
use ToyRobot\Simulator;

class SimulatorTest extends TestCase
{
    /**
     * @var \Mockery\MockInterface  Board mock.
     */
    protected $board;

    /**
     * @var \Mockery\MockInterface  Robot mock.
     */
    protected $robot;

    /**
     * \ToyRobot\Simulator  Simulator instance.
     */
    protected $simulator;



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
        $this->simulator = $this->mock(Simulator::class, [$this->robot]);
    }

    /**
     * Test running simulator.
     *
     * @covers \ToyRobot\Simulator::run
     * @return void
     */
    public function testRun()
    {
        $this->robot->shouldReceive('execute')->times(5);
        $this->simulator->run('tests/data/test.txt');
    }
}
