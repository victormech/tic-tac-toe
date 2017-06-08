<?php

namespace AppBundle\Mapper;

use AppBundle\Builder\GameBuilder;
use AppBundle\Builder\TicTacToeBuilder;
use TicTacToeGame\AI\EvaluatorInterface;
use TicTacToeGame\AI\MiniMaxCpuEvaluator;
use TicTacToeGame\AI\RandomCpuEvaluator;
use TicTacToeGame\Enum\StateTypeEnum;
use TicTacToeGame\GameRules;
use TicTacToeGame\ValueObject\Game;
use TicTacToeGame\ValueObject\GameBoard;
use TicTacToeGame\ValueObject\Player\CpuPlayer;
use TicTacToeGame\ValueObject\Player\HumanPlayer;
use TicTacToeGame\ValueObject\Player\PlayerInterface;

/**
 * Class TicTacToeGameMapper
 * @package AppBundle\Mapper
 */
class TicTacToeGameMapper
{
    const LEVEL_EASY = 'EASY';
    const LEVEL_HARD = 'HARD';

    /**
     * @param string $jsonData
     * @return GameRules
     */
    public function mapFromJson(string $jsonData) : GameRules
    {
        $gameData = json_decode($jsonData);
        return $this->buildTicTacToeGame($gameData);
    }

    /**
     * @param \stdClass $game
     * @return GameRules
     */
    private function buildTicTacToeGame(\stdClass $game) : GameRules
    {
        $ticTacToeGame = $this->buildGame($game);
        $cpuEvaluator = $this->buildCpuEvaluator($game, $ticTacToeGame);

        $builder = new TicTacToeBuilder();
        $builder->setGame($ticTacToeGame);
        $builder->setCpuEvaluator($cpuEvaluator);

        return $builder->build();
    }

    /**
     * @param \stdClass $game
     * @return Game
     */
    private function buildGame(\stdClass $game) : Game
    {
        $builder = new GameBuilder();
        $builder->setGameBoard(new GameBoard($game->board));
        $builder->setPlayer($this->buildPlayer($game));

        return $builder->build();
    }

    /**
     * @param \stdClass $game
     * @return PlayerInterface
     */
    private function buildPlayer(\stdClass $game) : PlayerInterface
    {
        return StateTypeEnum::HUMAN_PLAYER == $game->currentPlayer->type
            ? new HumanPlayer()
            : new CpuPlayer();
    }

    /**
     * @param \stdClass $game
     * @param Game $ticTacToeGame
     * @return MiniMaxCpuEvaluator|RandomCpuEvaluator
     */
    private function buildCpuEvaluator(\stdClass $game, Game $ticTacToeGame)
    {
        return self::LEVEL_EASY == $game->level
            ? new RandomCpuEvaluator($ticTacToeGame)
            : new MiniMaxCpuEvaluator($ticTacToeGame);
    }

    /**
     * @param GameRules $rules
     * @return string
     */
    public function mapToJson(GameRules $rules) : string
    {
        $result = [];
        $game = $rules->getGame();
        $currentPlayer = $game->getCurrentPlayer();
        $lastState = $rules->getLastAddedState();

        $result['stats']['isGameOver'] = $rules->isGameOver();
        $result['stats']['isDraw'] = $rules->isDraw();
        $result['stats']['isVictory'] = $rules->isVictory();
        $result['stats']['winner'] = $this->getWinnerType($rules);
        $result['board'] = $game->getGameBoard()->getRawBoard();

        $result['currentPlayer']['type'] = $currentPlayer->getStateType();
        $result['level'] = $this->getLevelFromInstance($rules->getGameEvaluator());

        $result['state']['x'] = $lastState->getX();
        $result['state']['y'] = $lastState->getY();
        $result['state']['player']['type'] = $lastState->getValue();

        return json_encode($result);
    }

    /**
     * @param EvaluatorInterface $evaluator
     * @return string
     */
    private function getLevelFromInstance(EvaluatorInterface $evaluator) : string
    {
        return ($evaluator instanceof RandomCpuEvaluator)
            ? self::LEVEL_EASY
            : self::LEVEL_HARD;
    }

    /**
     * @param GameRules $rules
     * @return array
     */
    private function getWinnerType(GameRules $rules)
    {
        $result = [];
        if ($rules->isGameOver() && $rules->isVictory()) {
            $result['type'] = $rules->getWinner()->getStateType();
        }

        return $result;
    }
}
