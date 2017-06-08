<?php

namespace TicTacToeGame;

use TicTacToeGame\Enum\StateTypeEnum;
use TicTacToeGame\ValueObject\Game;
use TicTacToeGame\ValueObject\Player\CpuPlayer;
use TicTacToeGame\ValueObject\Player\HumanPlayer;
use TicTacToeGame\ValueObject\Player\PlayerInterface;

class GameScore
{
    /**
     * @var Game
     */
    private $game;

    /**
     * @var PlayerInterface
     */
    private $winner;

    /**
     * @var int
     */
    private $winnerType;

    /**
     * GameScore constructor.
     * @param $game
     */
    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    /**
     * @return bool
     */
    public function isGameOver() : bool
    {
        return $this->isDraw() || $this->isVictory();
    }

    /**
     * @return PlayerInterface
     */
    public function getWinner() : PlayerInterface
    {
        return $this->winner;
    }

    /**
     * @return bool
     */
    public function isDraw() : bool
    {
        return $this->game->getGameBoard()->isFullBoard();
    }

    /**
     * @return int
     */
    public function getScore() : int
    {
        if ($this->isVictory()) {
            return $this->winner->getValue();
        }

        return 0;
    }

    /**
     * @return bool
     */
    private function isVictory() : bool
    {
        if ($this->evaluateVictoryConditions()) {
            $this->evaluateWinner();

            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    private function evaluateVictoryConditions() : bool
    {
        return (bool) ($this->hasFullRow() || $this->hasFullColumn() || $this->hasDiagonal());
    }

    /**
     * @return bool
     */
    private function hasFullRow() : bool
    {
        $gameBoard = $this->game->getGameBoard();
        $board = $gameBoard->getRawBoard();

        foreach ($board as $row) {
            if (1 === count(array_unique($row)) && StateTypeEnum::EMPTY !== $row[0]) {
                $this->winnerType = $row[0];

                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    private function hasFullColumn() : bool
    {
        $gameBoard = $this->game->getGameBoard();
        $rawBoard = $gameBoard->getRawBoard();
        $gridSize = $gameBoard->getGridSize();

        for ($i = 0; $i < $gridSize; $i++) {
            $total = count(array_unique([
                $rawBoard[0][$i],
                $rawBoard[1][$i],
                $rawBoard[2][$i]
            ]));

            if (1 === $total && StateTypeEnum::EMPTY !== $rawBoard[0][$i]) {
                $this->winnerType = $rawBoard[0][$i];

                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    private function hasDiagonal() : bool
    {
        $gameBoard = $this->game->getGameBoard();
        $rawBoard = $gameBoard->getRawBoard();
        $gridSize = $gameBoard->getGridSize();

        $frontDiagonal = [];
        $backDiagonal = [];

        for ($i = 0; $i < $gridSize; $i++) {
            $frontDiagonal[] = $rawBoard[$i][$i];
            $backDiagonal[] = $rawBoard[$i][($gridSize - 1) - $i];
        }

        $totalUniqueFront = count(array_unique($frontDiagonal));
        $totalUniqueBack = count(array_unique($backDiagonal));

        $hasFrontDiagonal = (1 === $totalUniqueFront) && StateTypeEnum::EMPTY !== $rawBoard[0][0];
        $hasBackDiagonal = (1 === $totalUniqueBack) && StateTypeEnum::EMPTY !== $rawBoard[0][$gridSize -1];

        if ($hasFrontDiagonal) {
            $this->winnerType = $rawBoard[0][0];
        }

        if ($hasBackDiagonal) {
            $this->winnerType = $rawBoard[0][$gridSize -1];
        }

        return $hasFrontDiagonal || $hasBackDiagonal;
    }

    /**
     * @return PlayerInterface
     */
    private function evaluateWinner() : PlayerInterface
    {
        if (StateTypeEnum::HUMAN_PLAYER === $this->winnerType) {
            return $this->winner = new HumanPlayer();
        }

        return $this->winner = new CpuPlayer();
    }
}
