<?php

namespace ToyRobot;

use InvalidArgumentException;

class Robot
{
    /**
     * @var string  Permissible methods.
     */
    const METHOD_PLACE  = 'PLACE';
    const METHOD_MOVE   = 'MOVE';
    const METHOD_LEFT   = 'LEFT';
    const METHOD_RIGHT  = 'RIGHT';
    const METHOD_REPORT = 'REPORT';

    /**
     * @var string  Permissible directions on board.
     */
    const DIRECTION_NORTH = 'NORTH';
    const DIRECTION_EAST  = 'EAST';
    const DIRECTION_SOUTH = 'SOUTH';
    const DIRECTION_WEST  = 'WEST';

    /**
     * @var string  Permissible rotations on board.
     */
    const ROTATION_LEFT  = 'LEFT';
    const ROTATION_RIGHT = 'RIGHT';

    /**
     * @var \ToyRobot\Board
     */
    protected $board;

    /**
     * @var integer  Horizontal position on board.
     */
    protected $x;

    /**
     * @var integer  Vertical position on board.
     */
    protected $y;

    /**
     * @var string  Direction facing on board.
     */
    protected $direction;


    /**
     * Create new Robot instance.
     *
     * @param  \ToyRobot\Board  $board
     * @return void
     */
    public function __construct(Board $board)
    {
        $this->board = $board;
    }

    /**
     * Parse and execute command.
     *
     * @param  string  $command
     * @return void
     */
    public function execute($command)
    {
        // Parse command and extract arguments
        extract($this->parseCommand($command));

        // Execute robot method with arguments
        switch ($method) {
            case self::METHOD_PLACE:
                $this->place($x, $y, $direction);
                break;

            case self::METHOD_MOVE:
                $this->move();
                break;

            case self::METHOD_LEFT:
            case self::METHOD_RIGHT:
                $this->rotate($method);
                break;

            case self::METHOD_REPORT:
                echo $this->report() . PHP_EOL;
                break;
        }
    }

    /**
     * Parse command, extracting method, x, y, and direction where applicable.
     *
     * @param  string  $command
     * @return array
     */
    protected function parseCommand($command)
    {
        // Extract method and arguments from command
        preg_match(
            '/^' .
            '(?P<method>' . $this->getMethods('|') . ')' .
            '(\s' .
                '(?P<x>\d+)\s?,' .
                '(?P<y>\d+)\s?,' .
                '(?P<direction>' . $this->getDirections('|') . ')' .
            ')?' .
            '$/',
            strtoupper($command),
            $args
        );

        // Extract captured arguments with fallback defaults
        $method = $args['method'] ?? null;
        $x = $args['x'] ?? 0;
        $y = $args['y'] ?? 0;
        $direction = $args['direction'] ?? self::DIRECTION_NORTH;

        return compact('method', 'x', 'y', 'direction');
    }

    /**
     * Place robot on board.
     *
     * @param  integer  $x
     * @param  integer  $y
     * @param  string   $direction
     * @return void
     */
    public function place($x, $y, $direction)
    {
        // Check if new x,y coordinates within board bounds
        if (! $this->board->withinBounds($x, $y)) {
            throw new InvalidArgumentException(sprintf('Coordinates (%d,%d) outside board boundaries.', $x, $y));
        }

        // Check if supplied direction is permissible
        if (! $this->isPermissibleDirection($direction)) {
            throw new InvalidArgumentException(sprintf('Direction (%s) is not recognised.', $direction));
        }

        // Set robot position and direction
        $this->x = $x;
        $this->y = $y;
        $this->direction = $direction;
    }

    /**
     * Move robot forward one unit in current direction.
     *
     * @return void
     */
    public function move()
    {
        // Check that robot is placed before executing command
        if (! $this->isPlaced()) return;

        // Get current robot position
        $x = $this->x;
        $y = $this->y;

        // Determine new position based on current direction
        switch ($this->direction) {
            case self::DIRECTION_NORTH:
                $y += 1;
                break;

            case self::DIRECTION_EAST:
                $x += 1;
                break;

            case self::DIRECTION_SOUTH:
                $y -= 1;
                break;

            case self::DIRECTION_WEST:
                $x -= 1;
                break;
        }

        // Check if new x,y coordinates within board bounds
        if (! $this->board->withinBounds($x, $y)) return;

        // Set robot position
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * Rotate robot in by rotation.
     *
     * @param  string  $rotation
     * @return void
     */
    public function rotate($rotation)
    {
        // Check that robot is placed before executing command
        if (! $this->isPlaced()) return;

        $this->direction = $this->resolveDirectionFromRotation($rotation);
    }

    /**
     * Report robot status - X,Y position and direction facing.
     *
     * @return string
     */
    public function report()
    {
        // Check that robot is placed before executing command
        if (! $this->isPlaced()) return;

        return sprintf('%d,%d,%s', $this->x, $this->y, $this->direction);
    }

    /**
     * Check whether robot has been placed on board.
     *
     * @return boolean
     */
    public function isPlaced()
    {
        return (! is_null($this->x) && ! is_null($this->y));
    }

    /**
     * Resolve robot direction from given rotation.
     *
     * @param  string  $rotation
     * @return string
     */
    protected function resolveDirectionFromRotation($rotation)
    {
        if (! $this->isPermissibleRotation($rotation)) {
            throw new InvalidArgumentException(sprintf('Rotation (%s) is not recognised.', $rotation));
        }

        // Determine direction of rotation - clockwise or anti-clockwise
        $clockwise = ($rotation === self::ROTATION_RIGHT);

        // Determine new direction based on current direction of rotation
        switch ($this->direction) {
            case self::DIRECTION_NORTH:
                return $clockwise ? self::DIRECTION_EAST : self::DIRECTION_WEST;

            case self::DIRECTION_EAST:
                return $clockwise ? self::DIRECTION_SOUTH : self::DIRECTION_NORTH;

            case self::DIRECTION_SOUTH:
                return $clockwise ? self::DIRECTION_WEST : self::DIRECTION_EAST;

            case self::DIRECTION_WEST:
                return $clockwise ? self::DIRECTION_NORTH : self::DIRECTION_SOUTH;
        }
    }

    /**
     * Get permissible methods as array or string.
     *
     * @param  string|null  $separator
     * @return array|string
     */
    protected function getMethods($separator = null)
    {
        $methods = [
            self::METHOD_PLACE,
            self::METHOD_MOVE,
            self::METHOD_LEFT,
            self::METHOD_RIGHT,
            self::METHOD_REPORT,
        ];

        return is_null($separator) ? $methods : implode($separator, $methods);
    }

    /**
     * Get permissible directions as array or string.
     *
     * @param  string|null  $separator
     * @return array|string
     */
    protected function getDirections($separator = null)
    {
        $directions = [
            self::DIRECTION_NORTH,
            self::DIRECTION_EAST,
            self::DIRECTION_SOUTH,
            self::DIRECTION_WEST,
        ];

        return is_null($separator) ? $directions : implode($separator, $directions);
    }

    /**
     * Get permissible rotations as array or string.
     *
     * @param  string|null  $separator
     * @return array|string
     */
    protected function getRotations($separator = null)
    {
        $rotations = [
            self::ROTATION_LEFT,
            self::ROTATION_RIGHT,
        ];

        return is_null($separator) ? $rotations : implode($separator, $rotations);
    }

    /**
     * Check whether given direction is a permissible direction.
     *
     * @param  string  $direction
     * @return boolean
     */
    protected function isPermissibleDirection($direction)
    {
        return in_array($direction, $this->getDirections());
    }

    /**
     * Check whether given rotation is a permissible rotation.
     *
     * @param  string  $rotation
     * @return boolean
     */
    protected function isPermissibleRotation($rotation)
    {
        return in_array($rotation, $this->getRotations());
    }
}
