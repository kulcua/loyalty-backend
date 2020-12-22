<?php

namespace Kulcua\ExtensionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/ne")
     */
    public function indexAction()
    {
        return new JsonResponse(['status' => 'OK']);
        // return $this->render('KulcuaExtensionBundle:Default:index.html.twig');
    }
}
