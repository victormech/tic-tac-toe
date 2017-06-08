<?php

namespace Tests\TicTacToeGame;

use PHPUnit\Framework\TestCase;
use TicTacToeGame\AI\MiniMaxCpuEvaluator;
use TicTacToeGame\Enum\StateTypeEnum;
use TicTacToeGame\Exception\InvalidNewStateException;
use TicTacToeGame\GameRules;
use TicTacToeGame\AI\RandomCpuEvaluator;
use TicTacToeGame\ValueObject\Game;
use TicTacToeGame\ValueObject\GameBoard;
use TicTacToeGame\ValueObject\Player\CpuPlayer;
use TicTacToeGame\ValueObject\Player\HumanPlayer;
use TicTacToeGame\ValueObject\State;

class GameRulesTest extends TestCase
{
    public function testCanBeCreated()
    {
        $game = new Game(new GameBoard(array()), new CpuPlayer());
        $evaluator = new RandomCpuEvaluator($game);
        $this->assertInstanceOf(GameRules::class, new GameRules($game, $evaluator));
    }

    public function testDraw()
    {
        $board = array_fill(0, 3, array_fill(0, 3, 1));
        $game = new Game(new GameBoard($board), new CpuPlayer());
        $rules = new GameRules($game, new MiniMaxCpuEvaluator($game));

        $this->assertTrue($rules->isDraw());
    }

    public function testCpuVictory()
    {
        $board = [
            [StateTypeEnum::CPU_PLAYER, StateTypeEnum::HUMAN_PLAYER, StateTypeEnum::EMPTY],
            [StateTypeEnum::CPU_PLAYER, StateTypeEnum::HUMAN_PLAYER, StateTypeEnum::HUMAN_PLAYER],
            [StateTypeEnum::EMPTY, StateTypeEnum::EMPTY, StateTypeEnum::EMPTY]
        ];

        $game = new Game(new GameBoard($board), new CpuPlayer());
        $rules = new GameRules($game, new MiniMaxCpuEvaluator($game));
        $rules->applyState($rules->evaluateCpuTurn());

        if ($rules->isGameOver() && !$rules->isDraw()) {
            $this->assertEquals(StateTypeEnum::CPU_PLAYER, $rules->getWinner()->getStateType());
        }
    }

    public function testRowVictory()
    {
        $board = [
            [StateTypeEnum::HUMAN_PLAYER, StateTypeEnum::HUMAN_PLAYER, StateTypeEnum::HUMAN_PLAYER],
            [StateTypeEnum::HUMAN_PLAYER, StateTypeEnum::CPU_PLAYER, StateTypeEnum::EMPTY],
            [StateTypeEnum::CPU_PLAYER, StateTypeEnum::CPU_PLAYER, StateTypeEnum::EMPTY]
        ];

        $game = new Game(new GameBoard($board), new CpuPlayer());
        $rules = new GameRules($game, new MiniMaxCpuEvaluator($game));

        $this->assertTrue($rules->isVictory());
    }

    public function testCollumnVictory()
    {
        $board = [
            [StateTypeEnum::HUMAN_PLAYER, StateTypeEnum::HUMAN_PLAYER, StateTypeEnum::CPU_PLAYER],
            [StateTypeEnum::HUMAN_PLAYER, StateTypeEnum::CPU_PLAYER, StateTypeEnum::EMPTY],
            [StateTypeEnum::HUMAN_PLAYER, StateTypeEnum::CPU_PLAYER, StateTypeEnum::EMPTY]
        ];

        $game = new Game(new GameBoard($board), new CpuPlayer());
        $rules = new GameRules($game, new MiniMaxCpuEvaluator($game));

        $this->assertTrue($rules->isVictory());
    }

    public function testDiagonalVictory()
    {
        $board = [
            [StateTypeEnum::HUMAN_PLAYER, StateTypeEnum::CPU_PLAYER, StateTypeEnum::CPU_PLAYER],
            [StateTypeEnum::HUMAN_PLAYER, StateTypeEnum::HUMAN_PLAYER, StateTypeEnum::EMPTY],
            [StateTypeEnum::EMPTY, StateTypeEnum::CPU_PLAYER, StateTypeEnum::HUMAN_PLAYER]
        ];

        $game = new Game(new GameBoard($board), new CpuPlayer());
        $rules = new GameRules($game, new MiniMaxCpuEvaluator($game));

        $this->assertTrue($rules->isVictory());
    }

    public function testBackDiagonalVictory()
    {
        $board = [
            [StateTypeEnum::EMPTY, StateTypeEnum::EMPTY, StateTypeEnum::CPU_PLAYER],
            [StateTypeEnum::HUMAN_PLAYER, StateTypeEnum::CPU_PLAYER, StateTypeEnum::EMPTY],
            [StateTypeEnum::CPU_PLAYER, StateTypeEnum::CPU_PLAYER, StateTypeEnum::HUMAN_PLAYER]
        ];

        $game = new Game(new GameBoard($board), new CpuPlayer());
        $rules = new GameRules($game, new MiniMaxCpuEvaluator($game));

        $this->assertTrue($rules->isVictory());
    }

    /**
     * @expectedException \TicTacToeGame\Exception\InvalidNewStateException
     */
    public function testApplyState()
    {
        $board = [
            [StateTypeEnum::EMPTY, StateTypeEnum::EMPTY, StateTypeEnum::CPU_PLAYER],
            [StateTypeEnum::HUMAN_PLAYER, StateTypeEnum::CPU_PLAYER, StateTypeEnum::EMPTY],
            [StateTypeEnum::CPU_PLAYER, StateTypeEnum::CPU_PLAYER, StateTypeEnum::HUMAN_PLAYER]
        ];
        $game = new Game(new GameBoard($board), new CpuPlayer());
        $rules = new GameRules($game, new MiniMaxCpuEvaluator($game));
        $rules->applyState($rules->evaluateCpuTurn());
        $rules->applyState(new State(1, 1, 1));
    }

    /**
     * @expectedException \TicTacToeGame\Exception\InvalidNewStateException
     */
    public function testApplyStateFullboard()
    {
        $board = [
            [StateTypeEnum::HUMAN_PLAYER, StateTypeEnum::HUMAN_PLAYER, StateTypeEnum::CPU_PLAYER],
            [StateTypeEnum::HUMAN_PLAYER, StateTypeEnum::CPU_PLAYER, StateTypeEnum::CPU_PLAYER],
            [StateTypeEnum::CPU_PLAYER, StateTypeEnum::CPU_PLAYER, StateTypeEnum::HUMAN_PLAYER]
        ];
        $game = new Game(new GameBoard($board), new CpuPlayer());
        $rules = new GameRules($game, new MiniMaxCpuEvaluator($game));
        $rules->applyState(new State(1, 1, 1));
    }
}
