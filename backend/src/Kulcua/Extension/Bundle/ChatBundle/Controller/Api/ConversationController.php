<?php

namespace Kulcua\Extension\Bundle\ChatBundle\Controller\Api;

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
use Kulcua\Extension\Bundle\ChatBundle\Form\Type\ConversationFormType;
use Kulcua\Extension\Component\Conversation\Domain\ReadModel\ConversationDetails;
use Kulcua\Extension\Component\Conversation\Domain\ReadModel\ConversationDetailsRepository;
use Kulcua\Extension\Component\Conversation\Domain\Conversation;
use Kulcua\Extension\Component\Conversation\Domain\ConversationId;
use OpenLoyalty\Bundle\UserBundle\Entity\User;
use OpenLoyalty\Component\Customer\Domain\ReadModel\CustomerDetails;
use Kulcua\Extension\Component\Conversation\Domain\Command\CreateConversation;

class ConversationController extends FOSRestController
{
     /**
     * Method will return complete list of all conversations.
     *
     * @Route(name="kc.conversation.list", path="/conversation")
     * @Route(name="kc.conversation.customer.list", path="/customer/conversation")
     * @Route(name="kc.conversation.seller.list", path="/seller/conversation")
     * @Security("is_granted('LIST_CONVERSATIONS') or is_granted('LIST_CURRENT_CUSTOMER_TRANSACTIONS')")
     * @Method("GET")
     *
     * @ApiDoc(
     *     name="get conversations list",
     *     section="Chat Conversation",
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
     * @QueryParam(name="participantIds", nullable=true, description="participantIds"))
     * @QueryParam(name="participantNames", nullable=true, description="participantNames"))
     * @QueryParam(name="lastMessageSnippet", nullable=true, description="lastMessageSnippet"))
     */
    public function listAction(Request $request, ParamFetcher $paramFetcher): View
    {
        $params = $this->get('oloy.user.param_manager')->stripNulls($paramFetcher->all());

        /** @var User $user */
        $user = $this->getUser();

        if ($this->isGranted('ROLE_PARTICIPANT')) {
            $params['participantIds'] = $user->getId();
        }
        $pagination = $this->get('oloy.pagination')->handleFromRequest($request, 'lastMessageSnippet', 'DESC');

        /** @var ConversationDetailsRepository $repo */
        $repo = $this->get(ConversationDetailsRepository::class);

        $conversations = $repo->findByParametersPaginated(
            $params,
            false,
            $pagination->getPage(),
            $pagination->getPerPage(),
            $pagination->getSort(),
            $pagination->getSortDirection()
        );
        $total = $repo->countTotal($params, $request->get('strict', false));;

        return $this->view([
            'conversations' => $conversations,
            'total' => $total,
        ], 200);
    }

    /**
     * Method allows to create new conversation in system.
     *
     * @Route(name="kc.conversation.create", path="/conversation")
     * @Method("POST")
    //  Security("is_granted('CREATE_CONVERSATION')")
     * @ApiDoc(
     *     name="Create conversation",
     *     section="Chat Conversation",
     *     input={"class" = "Kulcua\Extension\Bundle\ChatBundle\Form\Type\ConversationFormType", "name" = "conversation"},
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
        $form = $this->get('form.factory')->createNamed('conversation', ConversationFormType::class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            
            $conversationId = new ConversationId($this->get('broadway.uuid.generator')->generate());
            
            $this->get('broadway.command_handling.command_bus')->dispatch(
                new CreateConversation(
                    $conversationId,
                    $data
                )
            );

            return $this->view(['conversationId' => (string) $conversationId]);
        }

        return $this->view($form->getErrors(), Response::HTTP_BAD_REQUEST);
    }
}
