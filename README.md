# Toy Robot Simulator

## Table of contents:

* [Description](./README.md#description)
* [Setup](./README.md#setup)
* [Run Simulator](./README.md#run-simulator)
* [Run Tests](./README.md#run-tests)
* [Development Considerations](./README.md#development-considerations)
* [Examples](./README.md#examples)

## Description

* The application is a simulation of a toy robot moving on a square tabletop, of dimensions 5 units x 5 units.

* There are no other obstructions on the table surface.

* The robot is free to roam around the surface of the table, but must be prevented from falling to destruction. Any movement that would result in the robot falling from the table must be prevented, however further valid movement commands must still be allowed.

Create an application that can read in commands of the following form:
```
PLACE X,Y,F
MOVE
LEFT
RIGHT
REPORT
```

* `PLACE` will put the toy robot on the table in position X,Y and facing NORTH, SOUTH, EAST or WEST.

* The origin (0,0) can be considered to be the SOUTH WEST most corner.

* The first valid command to the robot is a `PLACE` command, after that, any sequence of commands may be issued, in any order, including another `PLACE` command. The application should discard all commands in the sequence until a valid `PLACE` command has been executed

* `MOVE` will move the toy robot one unit forward in the direction it is currently facing.

* `LEFT` and `RIGHT` will rotate the robot 90 degrees in the specified direction without changing the position of the robot.

* `REPORT` will announce the X,Y and F of the robot. This can be in any form, but standard output is sufficient.

* A robot that is not on the table can choose to ignore the `MOVE`, `LEFT`, `RIGHT` and `REPORT` commands.

* Input can be from a file, or from standard input, as the developer chooses.

* Provide test data to exercise the application.

### Constraints

* The toy robot must not fall off the table during movement. This also includes the initial placement of the toy robot.

* Any move that would cause the robot to fall must be ignored.

### Example Input and Output:

#### Example a

    PLACE 0,0,NORTH
    MOVE
    REPORT

Expected output:

    0,1,NORTH

#### Example b

    PLACE 0,0,NORTH
    LEFT
    REPORT

Expected output:

    0,0,WEST

#### Example c

    PLACE 1,2,EAST
    MOVE
    MOVE
    LEFT
    MOVE
    REPORT

Expected output

    3,3,NORTH

### Deliverables

* There must be a way to supply the application with input data via text file.

* The application must run and you should provide sufficient evidence that your solution is complete by, as a minimum, indicating that it works correctly against the supplied test data.

* The submission should be production quality PHP code. We are not looking for a gold plated solution, but the code should be maintainable and extensible.

* You may not use any external libraries to solve this problem, but you may use external libraries or tools for building or testing purposes. Specifically, you may use unit testing libraries or build tools.

* You should provide a readme file, detailing installation and execution instruction as well as a brief summary of assumptions and design decisions made.

* The submission should be provided via GitHub, Bitbucket or some other online version control system

## Setup

1. The host computer must be running PHP 7.0+.

2. Clone this repository:

    ```git clone git@github.com:robsimpkins/toy-robot-php.git```

3. Change directory into the repository directory:

    ```cd toy-robot-php```

4. Run project dependencies:

    ```composer install```

### Run Simulator
The simulator can be given commands either via the CLI or from a file:

    php simulate

    php simulate test-1.txt

### Run Tests
TBC

## Development Considerations
The solution to this puzzle was developed with [SOLID](https://en.wikipedia.org/wiki/SOLID_(object-oriented_design)) design principles in mind.

* **Single Responsibility** - the three classes that comprise the solution each have their own distinct responsibilities. Read in command input and execute on robot. Parse, interpret and execute commands. Determine the positionality of coordinates on the board.
* **Open/Closed** - the three classes have public functions to set inputs and get outputs. Internal attributes and methods are protected to allow for extension through inheritance, should this be required.
* **Liskov Substitution** - the use of dependency injection supports the replacement of each class by means of a subtype.
* **Interface Segregation** - the solution developed was not complicated enough to warrant interfaces. Had there been multiple types of robot, then an interface for the `execute` and output methods would have been worthwhile.
* **Dependency Inversion** - the solution developed was not complicated enough to warrant dependency inversion.

The solution makes one assumption, that being when a `PLACE` command is called without any arguments, the x,y coordinates and direction will default to 0,0,NORTH.

The puzzle solution is readily extendible in the following manners:

* Changeable board size.
* Different robot with altered commands can be injected.
* Robot methods could be modified to accept paramters. E.g. the `move` function could accept a distance.
* Board and robot could be modified to operate in three-dimensions.

## Examples
The repository includes four test files with sample commands.

    php simulate test-1.txt
    // Expected output: 0,1,NORTH

    php simulate test-2.txt
    // Expected output: 0,0,WEST

    php simulate test-3.txt
    // Expected output: 3,3,NORTH

    php simulate test-4.txt
    // Expected output: 4,4,EAST
