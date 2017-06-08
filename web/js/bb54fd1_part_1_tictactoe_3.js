var humanPlayer = {type: 1, asset: 'X'};
var cpuPlayer = {type: -1, asset: 'O'};
var emptyType = {type: 0};

var difficultLevel = 'EASY';
var gameBoard = [];
var currentPlayer = humanPlayer;
var nextGameStats = {};
var gameLocked = false;

$(document).ready(function () {
    if (resourceUrl == '') {
        alert('Error, Api url not found');
        return false;
    }

    activateMenu();
    makeBoard();
});

function initGame() {
    setDifficultLevel();
    initGameBoard();
    activateClick();
    $('#restart_button').click(restartGame);
}

function activateClick() {
    $(document).on('click', 'td', boardClick);
}

function deactivateClick() {
    $(document).off('click', 'td', boardClick);
}

function boardClick(event) {
    event.preventDefault();

    if (gameLocked) {
        return false;
    }

    var parent = $(this).parent().index();
    var position = $(this).index();
    var state = {x: parent, y: position, player: currentPlayer};

    if (isEmptyState(state.x, state.y)) {
        $(this).html(currentPlayer.asset);
        setBoardState(state);
        sendState(state)
    }

}

function setBoardState(state) {
    gameBoard[state.x][state.y] = state.player.type;
}

function sendState(state) {
    var gameData = {
        board: gameBoard,
        currentPlayer: cpuPlayer,
        level: difficultLevel,
        state: state
    };
    var gameDataJson = JSON.stringify(gameData);
    var url = resourceUrl + "/" + gameDataJson;

    gameLocked = true;
    openLoadscreen();

    $.get( url , function( data ) {
        nextGameStats = JSON.parse(data);
        closeLoadScreen();
    });
}

function setNewGameState() {
    var state = nextGameStats.state;
    var cell = $('table tr:eq(' + state.x + ') td:eq(' + state.y + ')');

    if (isEmptyState(state.x, state.y)) {
        $(cell).html(getPlayerAsset(state.player.type));
        updateGameBoard(state.x, state.y, state.player.type);
    }

    evaluteGameResult();
}

function evaluteGameResult() {

    if (nextGameStats.stats.isGameOver) {

        if (nextGameStats.stats.isDraw) {
            showEndGameMessage('Nobody wins! It is a DRAW!\nPress RESTART to restart the game!');

            return false;
        }

        if (nextGameStats.stats.isVictory) {
            showEndGameMessage(getWinnerMessage(nextGameStats.stats.winner));

            return false;
        }
    }

    gameLocked = false;

}

function getWinnerMessage(winner) {
    if (humanPlayer.type == winner.type) {
        return "You Win! Gongratulations!\nPress RESTART to restart the game!";
    }

    if (cpuPlayer.type == winner.type) {
        return "You Lose! Try again!\nPress RESTART to restart the game!";
    }
}

function showEndGameMessage(msg) {
    alert(msg);
}

function getPlayerAsset(type) {
    if (humanPlayer.type == type) {
        return humanPlayer.asset;
    }

    return cpuPlayer.asset;
}

function openLoadscreen() {
    var loadingImg = $('#loading_image').attr('src');

    $('body')
        .append("<div id='blackscreen'></div>")
        .append("<div id='loadimage'><img src='"+ loadingImg +"' ></div>");

    $('#blackscreen').fadeIn('slow', function () {
        $('#loadimage').css('display', 'block');
        $('#loadimage img')
            .center()
            .fadeTo('normal',1);
    });

}

function closeLoadScreen() {
    $('#loadimage').css('display', 'none');

    $('#blackscreen').fadeOut(function () {
        $('#blackscreen').remove();
        $('#loadimage').remove();
        setNewGameState();
    });
}

function isEmptyState(x, y) {
    return emptyType.type == gameBoard[x][y];
}

function updateGameBoard(x, y, type) {
    gameBoard[x][y] = type;
}

function initGameBoard() {
    gameBoard = [
        [0, 0, 0],
        [0, 0, 0],
        [0, 0, 0]
    ];
}

function makeBoard() {

    var rowstart = '<tr>';
    var rowend = '</tr>';
    var cell = '<td></td>';
    var boardsize = 3;
    var boardrow = rowstart;

    for (var i = 0; i < boardsize; i++) {
        boardrow += cell;
    }

    boardrow += rowend;

    for (var c = 0; c < boardsize; c++) {
        $('#board > tbody').append(boardrow);
    }
}

function activateMenu() {
    $('#play_button').click(function (event) {
        event.preventDefault();

        $('#menu_screen').fadeOut(function () {
            $('#game').css('display','table');
            $('#menu_screen').css('display', 'none');
            $('#game').fadeTo('normal', 1, initGame);
        });
    });
}

function setDifficultLevel() {
    difficultLevel = $("#game_form input[type='radio']:checked").val();
}

function restartGame() {
    if (confirm('Are you sure about this?')) {
        location.reload();
    }
}

