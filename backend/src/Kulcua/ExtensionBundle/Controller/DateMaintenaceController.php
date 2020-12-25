<?php

namespace Kulcua\ExtensionBundle\Controller;

// use Symfony\Bundle\FrameworkBundle\Controller\Controller;
// use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;

class DateMaintenaceController extends FOSRestController
{
    /**
     * @Route(name="app.date_maintenace.index", path="/dateMaintenace")
     * @Method("GET")
     *
     * @ApiDoc(
     *     name="New date maintenace list",
     *     section="Date Maintenace",
     *     statusCodes={
     *       200="Returned when successful",
     *     }
     * )
     *
     * @param Request $request
     *
     * @return View
     */
    public function indexController(Request $request)
    {
        return $this->view(['data' => ['data']]);
    }
}
