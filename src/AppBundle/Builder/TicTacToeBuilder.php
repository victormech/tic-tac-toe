<?php

namespace AppBundle\Builder;

use TicTacToeGame\AI\EvaluatorInterface;
use TicTacToeGame\GameRules;
use TicTacToeGame\ValueObject\Game;

class TicTacToeBuilder implements BuilderInterface
{
    /**
     * @var Game
     */
    private $game;

    /**
     * @var EvaluatorInterface
     */
    private $cpuEvaluator;

    /**
     * @return GameRules
     */
    public function build() : GameRules
    {
        return new GameRules($this->game, $this->cpuEvaluator);
    }

    /**
     * @param Game $game
     */
    public function setGame(Game $game)
    {
        $this->game = clone $game;
    }

    /**
     * @param EvaluatorInterface $evaluator
     */
    public function setCpuEvaluator(EvaluatorInterface $evaluator)
    {
        $this->cpuEvaluator = clone $evaluator;
    }
}