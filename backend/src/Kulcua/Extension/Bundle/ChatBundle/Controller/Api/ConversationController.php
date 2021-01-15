<?php

namespace Kulcua\Extension\Bundle\ChatBundle\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ConversationController extends Controller
{
    /**
     * @Route("/con")
     */
    public function indexAction()
    {
        return $this->render('ChatBundle:Default:index.html.twig');
    }
}
