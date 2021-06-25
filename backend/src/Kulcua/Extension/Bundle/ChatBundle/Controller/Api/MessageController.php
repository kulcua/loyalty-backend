<?php

namespace Kulcua\Extension\Bundle\ChatBundle\Controller\Api;

use Broadway\ReadModel\Repository;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Kulcua\Extension\Bundle\ChatBundle\Form\Type\MessageFormType;
use Kulcua\Extension\Component\Message\Domain\ReadModel\MessageDetails;
use Kulcua\Extension\Component\Message\Domain\ReadModel\MessageDetailsRepository;
use Kulcua\Extension\Component\Message\Domain\Message;
use Kulcua\Extension\Component\Message\Domain\MessageId;
use OpenLoyalty\Bundle\UserBundle\Entity\User;
use OpenLoyalty\Component\Customer\Domain\ReadModel\CustomerDetails;
use Kulcua\Extension\Component\Message\Domain\Command\CreateMessage;

class MessageController extends FOSRestController
{
     /**
     * Method will return complete list of all messages.
     *
     * @Route(name="kc.message.list", path="/message")
     * @Route(name="kc.message.customer.list", path="/customer/message")
     * @Route(name="kc.message.seller.list", path="/seller/message")
     //Security("is_granted('LIST_MESSAGES')")
     * @Method("GET")
     *
     * @ApiDoc(
     *     name="get messages list",
     *     section="Chat Message",
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
     * @QueryParam(name="conversationId", nullable=true, description="conversationId"))
     * @QueryParam(name="conversationParticipantIds", nullable=true, description="conversationParticipantIds"))
     * @QueryParam(name="senderName", nullable=true, description="senderName"))
     * @QueryParam(name="message", nullable=true, description="message"))
     */
    public function listAction(Request $request, ParamFetcher $paramFetcher): View
    {
        $params = $this->get('oloy.user.param_manager')->stripNulls($paramFetcher->all());

        /** @var User $user */
        $user = $this->getUser();

        if ($this->isGranted('ROLE_PARTICIPANT')) {
            $params['conversationParticipantIds'] = $user->getId();
        }
        $pagination = $this->get('oloy.pagination')->handleFromRequest($request, 'messageTimestamp', 'ASC');

        /** @var MessageDetailsRepository $repo */
        $repo = $this->get(MessageDetailsRepository::class);
        $total = $repo->countTotal($params, $request->get('strict', false));
        $perPage = 10;

        $page = (int) $total / $perPage;
        if($page == 0)
            $page = 1;

        $messages = $repo->findByParametersPaginated(
            $params,
            false,
            $page,
            $perPage,
            $pagination->getSort(),
            $pagination->getSortDirection()
        );

        return $this->view([
            'messages' => $messages,
            'total' => $total,
        ], 200);
    }

    /**
     * Method allows to create new message in system.
     *
     * @Route(name="kc.message.create", path="/message")
     * @Method("POST")
    //  Security("is_granted('CREATE_MESSAGE')")
     * @ApiDoc(
     *     name="Create message",
     *     section="Chat Message",
     *     input={"class" = "Kulcua\Extension\Bundle\ChatBundle\Form\Type\MessageFormType", "name" = "message"},
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
    public function createAction(Request $request): View
    {
        $form = $this->get('form.factory')->createNamed('message', MessageFormType::class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            
            $messageId = new MessageId($this->get('broadway.uuid.generator')->generate());
            
            $this->get('broadway.command_handling.command_bus')->dispatch(
                new CreateMessage(
                    $messageId,
                    $data
                )
            );

            return $this->view(['messageId' => (string) $messageId]);
        }

        return $this->view($form->getErrors(), Response::HTTP_BAD_REQUEST);
    }
}
