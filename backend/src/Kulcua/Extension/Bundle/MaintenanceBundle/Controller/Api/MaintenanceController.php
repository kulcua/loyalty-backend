<?php

namespace Kulcua\Extension\Bundle\MaintenanceBundle\Controller\Api;

use Broadway\ReadModel\Repository;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Kulcua\Extension\Bundle\MaintenanceBundle\Form\Type\MaintenanceFormType;
use Kulcua\Extension\Bundle\MaintenanceBundle\Form\Type\MaintenanceDetailsFormType;
use Kulcua\Extension\Component\Maintenance\Domain\ReadModel\MaintenanceDetails;
use Kulcua\Extension\Component\Maintenance\Domain\ReadModel\MaintenanceDetailsRepository;
use Kulcua\Extension\Component\Maintenance\Domain\Maintenance;
use Kulcua\Extension\Component\Maintenance\Domain\MaintenanceId;
use OpenLoyalty\Bundle\UserBundle\Entity\User;
use OpenLoyalty\Component\Customer\Domain\ReadModel\CustomerDetails;
use Kulcua\Extension\Component\Maintenance\Domain\Command\BookMaintenance;
use Kulcua\Extension\Component\Maintenance\Domain\Command\UpdateMaintenance;
use Kulcua\Extension\Component\Maintenance\Domain\Command\DeactivateMaintenance;

class MaintenanceController extends FOSRestController
{
    /**
     * Method will return complete list of all maintenances.
     *
     * @Route(name="kc.maintenance.list", path="/maintenance")
     * @Route(name="kc.maintenance.customer.list", path="/customer/maintenance")
     * @Route(name="kc.maintenance.seller.list", path="/seller/maintenance")
     * @Security("is_granted('LIST_MAINTENANCES')")
     * @Method("GET")
     *
     * @ApiDoc(
     *     name="get maintenances list",
     *     section="Maintenance",
     *     parameters={
     * {"name"="active", "dataType"="boolean", "required"=false, "description"="isActive"},
     *      {"name"="page", "dataType"="integer", "required"=false, "description"="Page number"},
     *      {"name"="perPage", "dataType"="integer", "required"=false, "description"="Number of elements per page"},
     *      {"name"="sort", "dataType"="string", "required"=false, "description"="Field to sort by"},
     *      {"name"="direction", "dataType"="asc|desc", "required"=false, "description"="Sorting direction"},
     *     }
     * )
     *
     * @param Request      $request
     * @param ParamFetcher $paramFetcher
     *
     * @return View
     *
     * @QueryParam(name="customerData_loyaltyCardNumber", nullable=true, description="loyaltyCardNumber"))
     * @QueryParam(name="customerData_name", nullable=true, description="customerName"))
     * @QueryParam(name="customerData_email", nullable=true, description="customerEmail"))
     * @QueryParam(name="customerData_phone", nullable=true, description="customerPhone"))
     * @QueryParam(name="customerId", nullable=true, description="customerId"))
     * @QueryParam(name="productSku", nullable=true, description="maintenanceId"))
     * @QueryParam(name="bookingTime", nullable=true, description="maintenanceId"))
     * @QueryParam(name="warrantyCenter", nullable=true, description="maintenanceId"))
     */
    public function listAction(Request $request, ParamFetcher $paramFetcher): View
    {
        $params = $this->get('oloy.user.param_manager')->stripNulls($paramFetcher->all());

        /** @var User $user */
        $user = $this->getUser();

        //use email for role
        if ($this->isGranted('ROLE_PARTICIPANT')) {
            $params['customerData.email'] = $user->getEmail();
        }
        $pagination = $this->get('oloy.pagination')->handleFromRequest($request, 'createdAt', 'DESC');

        /** @var MaintenanceDetailsRepository $repo */
        $repo = $this->get(MaintenanceDetailsRepository::class);

        $maintenances = $repo->findByParametersPaginated(
            $params,
            false,
            $pagination->getPage(),
            $pagination->getPerPage(),
            $pagination->getSort(),
            $pagination->getSortDirection()
        );
        $total = $repo->countTotal($params, $request->get('strict', false));;

        return $this->view([
            'maintenances' => $maintenances,
            'total' => $total,
        ], 200);
    }

    /**
     * Method allows to book new maintenance in system.
     *
     * @Route(name="kc.maintenance.book", path="/maintenance")
     * @Method("POST")
    //  Security("is_granted('CREATE_MAINTENANCE')")
     * @ApiDoc(
     *     name="Book maintenance",
     *     section="Maintenance",
     *     input={"class" = "Kulcua\Extension\Bundle\MaintenanceBundle\Form\Type\MaintenanceFormType", "name" = "maintenance"},
     *     statusCodes={
     *       200="Returned when successful",
     *       400="Returned when form contains errors",
     *     }
     * )
     *
     * @param Request $request
     *
     * @return View
     */
    public function bookAction(Request $request): View
    {
        $form = $this->get('form.factory')->createNamed('maintenance', MaintenanceFormType::class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            
            $maintenanceId = new MaintenanceId($this->get('broadway.uuid.generator')->generate());
            
            $this->get('broadway.command_handling.command_bus')->dispatch(
                new BookMaintenance(
                    $maintenanceId,
                    $data['maintenanceData'],
                    $data['customerData']
                )
            );

            return $this->view(['maintenanceId' => (string) $maintenanceId]);
        }

        return $this->view($form->getErrors(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * Method allows to update maintenance details.
     *
     * @param Request       $request
     * @param MaintenanceDetails $maintenance
     *
     * @return \FOS\RestBundle\View\View
     * @Route(name="kc.maintenance.edit", path="/maintenance/{maintenance}")
     * @Method("PUT")
     * @Security("is_granted('EDIT_MAINTENANCE', maintenance)")
     * @ApiDoc(
     *     name="Edit Maintenance",
     *     section="Maintenance",
     *     input={"class" = "Kulcua\Extension\Bundle\MaintenanceBundle\Form\Type\MaintenanceDetailsFormType", "name" = "maintenance"},
     *     statusCodes={
     *       200="Returned when successful",
     *       400="Returned when form contains errors",
     *     }
     * )
     */
    public function editMaintenanceAction(Request $request, MaintenanceDetails $maintenance)
    {
        $form = $this->get('form.factory')->createNamed('maintenance', MaintenanceDetailsFormType::class, [], [
            'method' => 'PUT',
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($this->get('kc.maintenance.form_handler.edit')->onSuccess($maintenance->getMaintenanceId(), $form) === true) {
                return $this->view([
                    'maintenanceId' => (string) $maintenance->getMaintenanceId(),
                ]);
            } else {
                return $this->view($form->getErrors(), Response::HTTP_BAD_REQUEST);
            }
        }

        return $this->view($form->getErrors(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * Method will return maintenance details.
     *
     * @Route(name="kc.maintenance.get", path="/maintenance/{maintenance}")
     * @Route(name="kc.maintenance.customer.get", path="/customer/maintenance/{maintenance}")
     * @Method("GET")
     * @Security("is_granted('VIEW', maintenance)")
     * @ApiDoc(
     *     name="get maintenance",
     *     section="Maintenance",
     * )
     *
     * @param MaintenanceDetails $maintenance
     *
     * @return View
     */
    public function getAction(MaintenanceDetails $maintenance): View
    {
        return $this->view($maintenance, 200);
    }

    /**
     * Change maintenance state action to active or inactive.
     *
     * @Route(name="kc.maintenance.change_state", path="/maintenance/{maintenance}/{active}", requirements={"active":"active|inactive"})
     * @Method("POST")
     * @Security("is_granted('EDIT_MAINTENANCE', maintenance)")
     * @ApiDoc(
     *     name="Change Maintenance state active",
     *     section="Maintenance"
     * )
     *
     * @param MaintenanceDetails $maintenance
     * @param                    $active
     *
     * @return View
     */
    public function DeactivateAction(MaintenanceDetails $maintenance, $active) : View
    {
        if ('active' === $active) {
            $maintenance->setActive(true);
        } elseif ('inactive' === $active) {
            $maintenance->setActive(false);
        }

        $this->get('broadway.command_handling.command_bus')->dispatch(
            new DeactivateMaintenance($maintenance->getMaintenanceId(), $maintenance->isActive())
        );

        return $this->view(['maintenanceId' => (string) $maintenance->getMaintenanceId()]);
    }
}
