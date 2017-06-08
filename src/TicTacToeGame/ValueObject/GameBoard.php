<?php

namespace TicTacToeGame\ValueObject;

use TicTacToeGame\Enum\StateTypeEnum;

class GameBoard
{
    /**
     * @var array
     */
    private $rawBoard;

    /**
     * GameBoard constructor.
     * @param array $rawBoard
     */
    public function __construct(array $rawBoard)
    {
        $this->rawBoard = $rawBoard;
    }

    /**
     * @return array
     */
    public function getRawBoard() : array
    {
        return $this->rawBoard;
    }

    /**
     * @return int
     */
    public function getGridSize() : int
    {
        return count($this->rawBoard);
    }

    /**
     * @param int $x
     * @param int $y
     * @return State
     */
    public function getStateOf(int $x, int $y) : State
    {
        return new State($x, $y, $this->rawBoard[$x][$y]);
    }

    /**
     * @param State $state
     * @return bool
     */
    public function isEmptyState(State $state) : bool
    {
        $targetState = $this->getStateOf($state->getX(), $state->getY());

        return (StateTypeEnum::EMPTY === $targetState->getValue());
    }

    /**
     * @return bool
     */
    public function isFullBoard() : bool
    {
        foreach ($this->rawBoard as $row) {
            if (in_array(StateTypeEnum::EMPTY, $row)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return array
     */
    public function getAllEmptyStates() : array
    {
        $emptyStates = [];

        for ($i = 0; $i < count($this->rawBoard); $i++) {
            $keys = array_keys($this->rawBoard[$i], StateTypeEnum::EMPTY);

            foreach ($keys as $key) {
                $emptyStates[] = $this->getStateOf($i, $key);
            }
        }

        return $emptyStates;
    }
}
