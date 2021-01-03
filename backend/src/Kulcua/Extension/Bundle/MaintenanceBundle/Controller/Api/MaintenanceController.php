<?php

namespace Kulcua\Extension\Bundle\MaintenanceBundle\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MaintenanceController extends Controller
{
    /**
     * @Route("/main")
     */
    public function indexAction()
    {
        return $this->render('MaintenanceBundle:Default:index.html.twig');
    }
}
