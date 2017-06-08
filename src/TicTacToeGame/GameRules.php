<?php

namespace TicTacToeGame;

use TicTacToeGame\AI\EvaluatorInterface;
use TicTacToeGame\Exception\InvalidNewStateException;
use TicTacToeGame\ValueObject\Game;
use TicTacToeGame\ValueObject\Player\PlayerInterface;
use TicTacToeGame\ValueObject\State;

class GameRules
{
    /**
     * @var Game
     */
    private $game;

    /**
     * @var EvaluatorInterface
     */
    private $gameEvaluator;

    /**
     * @var GameScore
     */
    private $gameScore;

    /**
     * @var array
     */
    private $states = [];

    /**
     * GameRules constructor.
     * @param Game $game
     * @param EvaluatorInterface $gameAI
     */
    public function __construct(Game $game, EvaluatorInterface $gameAI)
    {
        $this->game = $game;
        $this->gameEvaluator = $gameAI;
    }

    /**
     * @return Game
     */
    public function getGame() : Game
    {
        return $this->game;
    }

    /**
     * @return EvaluatorInterface
     */
    public function getGameEvaluator() : EvaluatorInterface
    {
        return $this->gameEvaluator;
    }

    /**
     * @return array
     */
    public function getStates() : array
    {
        return $this->states;
    }

    /**
     * @return State
     */
    public function getLastAddedState() : State
    {
        if (1 > count($this->states)) {
            return new State(0, 0, 0);
        }

        return $this->states[count($this->states) - 1];
    }

    /**
     * @return PlayerInterface
     */
    public function getWinner() : PlayerInterface
    {
        return $this->getGameScore()->getWinner();
    }

    /**
     * @return bool
     */
    public function isGameOver() : bool
    {
        return $this->getGameScore()->isGameOver();
    }

    /**
     * @return bool
     */
    public function isDraw() : bool
    {
        return $this->getGameScore()->isDraw();
    }

    /**
     * @return bool
     */
    public function isVictory() : bool
    {
        $score = $this->getGameScore();
        return $score->isGameOver() && !$score->isDraw();
    }

    /**
     * @return State
     */
    public function evaluateCpuTurn() : State
    {
        return $this->gameEvaluator->nextMove();
    }

    /**
     * @param State $state
     * @throws InvalidNewStateException
     */
    public function applyState(State $state)
    {
        $gameBoard = $this->game->getGameBoard();
        if (!$gameBoard->isEmptyState($state)) {
            throw new InvalidNewStateException("The provided State is not an empty State");
        }

        $this->game = $this->game->applyState($state);
        $this->gameScore = new GameScore($this->game);
        $this->states[] = clone $state;
    }

    /**
     * @return GameScore
     */
    private function getGameScore() : GameScore
    {
        if (is_null($this->gameScore)) {
            $this->gameScore = new GameScore($this->game);
        }

        return $this->gameScore;
    }
}
