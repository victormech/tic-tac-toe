<?php

namespace TicTacToeGame\AI;

use TicTacToeGame\Enum\StateTypeEnum;
use TicTacToeGame\Exception\InvalidNewStateException;
use TicTacToeGame\GameScore;
use TicTacToeGame\ValueObject\Game;
use TicTacToeGame\ValueObject\Player\PlayerInterface;
use TicTacToeGame\ValueObject\State;

class MiniMaxCpuEvaluator implements EvaluatorInterface
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
    public function nextMove() : State
    {
        $gameBoard = $this->game->getGameBoard();
        if ($gameBoard->isFullBoard()) {
            throw new InvalidNewStateException("Can't evalute new move. The game boad is full!");
        }

        $nextMove = $this->minimax($this->game);

        if (!array_key_exists('index', $nextMove)) {
            return $this->solveLastIndexState();
        }

        return $nextMove['index'];
    }

    /**
     * @param Game $game
     * @return GameScore
     */
    private function getGameScore(Game $game)
    {
        return (new GameScore($game));
    }

    /**
     * @param Game $game
     * @return array|mixed
     */
    private function minimax(Game $game)
    {
        $availableSpots = $game->getGameBoard()->getAllEmptyStates();
        $score = $this->getGameScore($game);

        if ($score->isGameOver()) {
            return ['score' => $score->getScore()];
        }

        $moves = [];

        foreach ($availableSpots as $spot) {
            $move = [];
            $newState = new State(
                $spot->getX(),
                $spot->getY(),
                $game->getCurrentPlayer()->getStateType()
            );

            $move['index'] = $newState;
            $possibleGame = $game->applyState($newState);
            $result = $this->minimax($possibleGame);

            $move['score'] = $result['score'];
            $moves[] = $move;
        }

        return $moves[$this->getBestStateIndex($moves, $game->getCurrentPlayer())];
    }

    /**
     * @param array $moves
     * @param PlayerInterface $player
     * @return int
     */
    private function getBestStateIndex(array $moves, PlayerInterface $player) : int
    {
        return StateTypeEnum::HUMAN_PLAYER === $player->getStateType()
            ? $this->evaluateHumanScore($moves)
            : $this->evaluateCpuScore($moves);
    }

    /**
     * @param array $moves
     * @return int
     */
    private function evaluateCpuScore(array $moves) : int
    {
        $bestScore = -10000;
        $bestMove = null;

        for ($i = 0; $i < count($moves); $i++) {
            if ($moves[$i]['score'] > $bestScore) {
                $bestScore = $moves[$i]['score'];
                $bestMove = $i;
            }
        }

        return $bestMove;
    }

    /**
     * @param array $moves
     * @return int
     */
    private function evaluateHumanScore(array $moves) : int
    {
        $bestScore = 10000;
        $bestMove = null;

        for ($i = 0; $i < count($moves); $i++) {
            if ($moves[$i]['score'] < $bestScore) {
                $bestScore = $moves[$i]['score'];
                $bestMove = $i;
            }
        }

        return $bestMove;
    }

    /**
     * @return State
     */
    private function solveLastIndexState() : State
    {
        $emptyStates = $this->game->getGameBoard()->getAllEmptyStates();
        return $emptyStates[0];
    }
}
