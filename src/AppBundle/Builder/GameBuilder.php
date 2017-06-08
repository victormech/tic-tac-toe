<?php

namespace AppBundle\Builder;

use TicTacToeGame\ValueObject\Game;
use TicTacToeGame\ValueObject\GameBoard;
use TicTacToeGame\ValueObject\Player\PlayerInterface;

class GameBuilder implements BuilderInterface
{
    /**
     * @var GameBoard
     */
    private $gameboard;

    /**
     * @var PlayerInterface
     */
    private $player;

    /**
     * @return Game
     */
    public function build() : Game
    {
        return new Game($this->gameboard, $this->player);
    }

    /**
     * @param GameBoard $gameBoard
     */
    public function setGameBoard(GameBoard $gameBoard)
    {
        $this->gameboard = clone $gameBoard;
    }

    /**
     * @param PlayerInterface $player
     */
    public function setPlayer(PlayerInterface $player)
    {
        $this->player = clone $player;
    }
}