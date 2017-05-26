<?php

namespace ToyRobot;

class Board
{
    /**
     * @var integer  Board height.
     */
    protected $height;

    /**
     * @var integer  Board width.
     */
    protected $width;


    /**
     * Create new Board instance.
     * 
     * @param  integer  $height
     * @param  integer  $width
     * @return void
     */
    public function __construct($height, $width)
    {
        $this->height = $height;
        $this->width = $width;
    }

    /**
     * Check whether given x,y coordinates fall within board bounds.
     * 
     * @param  integer  $x
     * @param  integer  $y
     * @return boolean
     */
    public function withinBounds($x, $y)
    {
        return (0 <= $x && $x < $this->width) && (0 <= $y && $y < $this->height);
    }
}
