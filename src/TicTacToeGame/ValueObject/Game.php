<?php

namespace TicTacToeGame\ValueObject;

use TicTacToeGame\Enum\StateTypeEnum;
use TicTacToeGame\ValueObject\Player\CpuPlayer;
use TicTacToeGame\ValueObject\Player\HumanPlayer;
use TicTacToeGame\ValueObject\Player\PlayerInterface;

class Game
{
    /**
     * @var GameBoard
     */
    private $gameBoard;

    /**
     * @var HumanPlayer
     */
    private $humanPlayer;

    /**
     * @var CpuPlayer
     */
    private $cpuPlayer;

    /**
     * @var PlayerInterface
     */
    private $currentPlayer;

    /**
     * Game constructor.
     * @param GameBoard $gameBoard
     */
    public function __construct(GameBoard $gameBoard, PlayerInterface $currentPlayer)
    {
        $this->gameBoard = $gameBoard;
        $this->currentPlayer = $currentPlayer;
    }

    /**
     * @return GameBoard
     */
    public function getGameBoard(): GameBoard
    {
        return $this->gameBoard;
    }

    /**
     * @return PlayerInterface
     */
    public function getCurrentPlayer(): PlayerInterface
    {
        return $this->currentPlayer;
    }

    /**
     * @return HumanPlayer
     */
    public function getHumanPlayer(): HumanPlayer
    {
        if (empty($this->humanPlayer)) {
            $this->humanPlayer = new HumanPlayer();
        }

        return $this->humanPlayer;
    }

    /**
     * @return CpuPlayer
     */
    public function getCpuPlayer(): CpuPlayer
    {
        if (empty($this->cpuPlayer)) {
            $this->cpuPlayer = new CpuPlayer();
        }

        return $this->cpuPlayer;
    }

    /**
     * @param State $state
     * @return Game
     */
    public function applyState(State $state) : Game
    {
        $rawBoard = $this->gameBoard->getRawBoard();
        $rawBoard[$state->getX()][$state->getY()] = $state->getValue();

        return new Game(
            new GameBoard($rawBoard),
            $this->getNextPlayer($state->getValue())
        );
    }

    /**
     * @param int $stateType
     * @return PlayerInterface
     */
    private function getNextPlayer(int $stateType) : PlayerInterface
    {
        return StateTypeEnum::HUMAN_PLAYER === $stateType
            ? $this->getCpuPlayer()
            : $this->getHumanPlayer();
    }
}
