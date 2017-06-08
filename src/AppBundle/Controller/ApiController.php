<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Service\TicTacToeService;

/**
 * Class ApiController
 * @package AppBundle\Controller
 */
class ApiController extends FOSRestController
{

    /**
     * @var TicTacToeService
     */
    private $service;

    /**
     * @param string $status
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a new state for the game",
     *   statusCodes = {
     *      200 = "Returned when successful",
     *      400 = "Returned when errors"
     *   },
     * )
     *
     * @Get("/api/v1/state/{game}", name="api_state")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getStateAction(string $game = '')
    {
        $this->service = $this->get('api.service.game');
        $nextState = $this->service->evaluateGameState($this->service->mapGameData($game));

        return $this->handleView($this->view($nextState));
    }
}