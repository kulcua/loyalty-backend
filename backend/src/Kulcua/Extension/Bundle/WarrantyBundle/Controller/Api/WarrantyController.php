<?php

namespace Kulcua\Extension\Bundle\WarrantyBundle\Controller\Api;

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
use Kulcua\Extension\Bundle\WarrantyBundle\Form\Type\WarrantyFormType;
use Kulcua\Extension\Bundle\WarrantyBundle\Form\Type\WarrantyDetailsFormType;
use Kulcua\Extension\Component\Warranty\Domain\ReadModel\WarrantyDetails;
use Kulcua\Extension\Component\Warranty\Domain\ReadModel\WarrantyDetailsRepository;
use Kulcua\Extension\Component\Warranty\Domain\Warranty;
use Kulcua\Extension\Component\Warranty\Domain\WarrantyId;
use OpenLoyalty\Bundle\UserBundle\Entity\User;
use OpenLoyalty\Component\Customer\Domain\ReadModel\CustomerDetails;
use Kulcua\Extension\Component\Warranty\Domain\Command\BookWarranty;
use Kulcua\Extension\Component\Warranty\Domain\Command\UpdateWarranty;
use Kulcua\Extension\Component\Warranty\Domain\Command\DeactivateWarranty;

class WarrantyController extends FOSRestController
{
    /**
     * Method will return complete list of all warrantys.
     *
     * @Route(name="kc.warranty.list", path="/warranty")
     * @Route(name="kc.warranty.customer.list", path="/customer/warranty")
     * @Route(name="kc.warranty.seller.list", path="/seller/warranty")
     * @Security("is_granted('LIST_WARRANTYS')")
     * @Method("GET")
     *
     * @ApiDoc(
     *     name="get warrantys list",
     *     section="Warranty",
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
     * @QueryParam(name="productSku", nullable=true, description="warrantyId"))
     * @QueryParam(name="bookingTime", nullable=true, description="warrantyId"))
     * @QueryParam(name="warrantyCenter", nullable=true, description="warrantyId"))
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

        /** @var WarrantyDetailsRepository $repo */
        $repo = $this->get(WarrantyDetailsRepository::class);

        $warrantys = $repo->findByParametersPaginated(
            $params,
            false,
            $pagination->getPage(),
            $pagination->getPerPage(),
            $pagination->getSort(),
            $pagination->getSortDirection()
        );
        $total = $repo->countTotal($params, $request->get('strict', false));;

        return $this->view([
            'warrantys' => $warrantys,
            'total' => $total,
        ], 200);
    }

    /**
     * Method allows to book new warranty in system.
     *
     * @Route(name="kc.warranty.book", path="/warranty")
     * @Method("POST")
    //  Security("is_granted('CREATE_WARRANTY')")
     * @ApiDoc(
     *     name="Book warranty",
     *     section="Warranty",
     *     input={"class" = "Kulcua\Extension\Bundle\WarrantyBundle\Form\Type\WarrantyFormType", "name" = "warranty"},
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
        $form = $this->get('form.factory')->createNamed('warranty', WarrantyFormType::class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            
            $warrantyId = new WarrantyId($this->get('broadway.uuid.generator')->generate());
            
            $this->get('broadway.command_handling.command_bus')->dispatch(
                new BookWarranty(
                    $warrantyId,
                    $data['warrantyData'],
                    $data['customerData']
                )
            );

            return $this->view(['warrantyId' => (string) $warrantyId]);
        }

        return $this->view($form->getErrors(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * Method allows to update warranty details.
     *
     * @param Request       $request
     * @param WarrantyDetails $warranty
     *
     * @return \FOS\RestBundle\View\View
     * @Route(name="kc.warranty.edit", path="/warranty/{warranty}")
     * @Method("PUT")
     * @Security("is_granted('EDIT_WARRANTY', warranty)")
     * @ApiDoc(
     *     name="Edit Warranty",
     *     section="Warranty",
     *     input={"class" = "Kulcua\Extension\Bundle\WarrantyBundle\Form\Type\WarrantyDetailsFormType", "name" = "warranty"},
     *     statusCodes={
     *       200="Returned when successful",
     *       400="Returned when form contains errors",
     *     }
     * )
     */
    public function editWarrantyAction(Request $request, WarrantyDetails $warranty)
    {
        $form = $this->get('form.factory')->createNamed('warranty', WarrantyDetailsFormType::class, [], [
            'method' => 'PUT',
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($this->get('kc.warranty.form_handler.edit')->onSuccess($warranty->getWarrantyId(), $form) === true) {
                return $this->view([
                    'warrantyId' => (string) $warranty->getWarrantyId(),
                ]);
            } else {
                return $this->view($form->getErrors(), Response::HTTP_BAD_REQUEST);
            }
        }

        return $this->view($form->getErrors(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * Method will return warranty details.
     *
     * @Route(name="kc.warranty.get", path="/warranty/{warranty}")
     * @Route(name="kc.warranty.customer.get", path="/customer/warranty/{warranty}")
     * @Method("GET")
     * @Security("is_granted('VIEW', warranty)")
     * @ApiDoc(
     *     name="get warranty",
     *     section="Warranty",
     * )
     *
     * @param WarrantyDetails $warranty
     *
     * @return View
     */
    public function getAction(WarrantyDetails $warranty): View
    {
        return $this->view($warranty, 200);
    }

    /**
     * Change warranty state action to active or inactive.
     *
     * @Route(name="kc.warranty.change_state", path="/warranty/{warranty}/{active}", requirements={"active":"active|inactive"})
     * @Method("POST")
     * @Security("is_granted('EDIT_WARRANTY', warranty)")
     * @ApiDoc(
     *     name="Change Warranty state active",
     *     section="Warranty"
     * )
     *
     * @param WarrantyDetails $warranty
     * @param                    $active
     *
     * @return View
     */
    public function DeactivateAction(WarrantyDetails $warranty, $active) : View
    {
        if ('active' === $active) {
            $warranty->setActive(true);
        } elseif ('inactive' === $active) {
            $warranty->setActive(false);
        }

        $this->get('broadway.command_handling.command_bus')->dispatch(
            new DeactivateWarranty($warranty->getWarrantyId(), $warranty->isActive())
        );

        return $this->view(['warrantyId' => (string) $warranty->getWarrantyId()]);
    }
}
