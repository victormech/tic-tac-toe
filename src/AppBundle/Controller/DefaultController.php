<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $resourceUrl = $this->generateUrl('api_state', [], UrlGeneratorInterface::ABSOLUTE_URL);
        return $this->render('default/index.html.twig', ['resourceUrl' => $resourceUrl]);
    }
}
