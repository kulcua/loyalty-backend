<?php

namespace Kulcua\Extension\Bundle\MaintenanceBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;

class MaintenanceController extends FOSRestController
{
    /**
     * @Route(name="app.maintenance.index", path="/maintenance/list")
     * @Method("GET")
     *
     * @ApiDoc(
     *     name="New maintenace list",
     *     section="Maintenance",
     *     statusCodes={
     *       200="Returned when successful",
     *     }
     * )
     *
     * @param Request $request
     *
     * @return View
     */
    public function indexAction()
    {
        return $this->render('MaintenanceBundle:Default:index.html.twig');
    }
}
