<?php

namespace TicTacToeGame\AI;

use TicTacToeGame\Enum\StateTypeEnum;
use TicTacToeGame\Exception\InvalidNewStateException;
use TicTacToeGame\ValueObject\Game;
use TicTacToeGame\ValueObject\State;

class RandomCpuEvaluator implements EvaluatorInterface
{
    /**
     * @var Game
     */
    private $game;

    /**
     * RandomCpuEvaluator constructor.
     * @param Game $game
     */
    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    /**
     * @return State
     */
    public function nextMove(): State
    {
        return $this->evaluateNextMove();
    }

    /**
     * @return State
     * @throws InvalidNewStateException
     */
    private function evaluateNextMove()
    {
        $gameBoard = $this->game->getGameBoard();
        if ($gameBoard->isFullBoard()) {
            throw new InvalidNewStateException("Can't evalute new move. The game boad is full!");
        }

        $state = $this->generateRandomState();

        while (!$gameBoard->isFullBoard() && !$gameBoard->isEmptyState($state)) {
            $state = $this->generateRandomState();
        }

        return $state;
    }

    /**
     * @return State
     */
    private function generateRandomState() : State
    {
        $gameBoard = $this->game->getGameBoard();
        $gridSize = $gameBoard->getGridSize();

        return new State(
            rand(0, $gridSize - 1),
            rand(0, $gridSize - 1),
            StateTypeEnum::CPU_PLAYER
        );
    }

    /**
     * @return Game
     */
    public function getGame(): Game
    {
        return $this->game;
    }

    /**
     * @param Game $game
     */
    public function setGame(Game $game)
    {
        $this->game = $game;
    }
}
