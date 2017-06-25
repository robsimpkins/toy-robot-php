<?php

namespace ToyRobot;

class Simulator
{
    /**
     * @var \ToyRobot\Robot  Robot instance.
     */
    protected $robot;


    /**
     * Create new Simulator instance.
     *
     * @param  \ToyRobot\Robot  $robot
     * @return void
     */
    public function __construct(Robot $robot)
    {
        $this->robot = $robot;
    }

    /**
     * Run simulator, reading instructions from given input source.
     *
     * @param  string   $source
     * @return void
     */
    public function run($source)
    {
        $handle = fopen($source, 'r');

        while (($command = fgets($handle))) {
            $this->robot->execute($command);
        }

        fclose($handle);
    }
}
