<?php

declare(strict_types=1);

namespace Kulcua\Extension\Bundle\ChatBundle\Controller\Api;

use Assert\InvalidArgumentException as AssertInvalidArgumentException;
use Broadway\CommandHandling\CommandBus;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View as FosView;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Kulcua\Extension\Bundle\ChatBundle\Form\Type\MessagePhotoFormType;
use Kulcua\Extension\Bundle\ChatBundle\Service\MessagePhotoContentGeneratorInterface;
use Kulcua\Extension\Component\Message\Domain\Message as DomainMessage;
use Kulcua\Extension\Component\Message\Domain\Command\MessagePhotoCommand;
use Kulcua\Extension\Component\Message\Domain\Command\CreatePhotoMessage;
use Kulcua\Extension\Component\Message\Domain\PhotoId;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Kulcua\Extension\Component\Message\Domain\MessageId;

/**
 * Class MessagePhotoController.
 */
class MessagePhotoController extends FOSRestController
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var MessagePhotoContentGeneratorInterface
     */
    private $photoService;

    /**
     * MessageController constructor.
     *
     * @param CommandBus                             $commandBus
     * @param FormFactory                            $formFactory
     * @param MessagePhotoContentGeneratorInterface $photoService
     */
    public function __construct(
        CommandBus $commandBus,
        FormFactory $formFactory,
        MessagePhotoContentGeneratorInterface $photoService
    ) {
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
        $this->photoService = $photoService;
    }

    /**
     * Get message photo.
     *
     * @Route(name="kc.message.get_photo", path="/message/photo/{messageId}")
     * @Method("GET")
     * @ApiDoc(
     *     name="Get message photo",
     *     section="Chat Message"
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getPhotoAction(Request $request): Response
    {
        $messageId = $request->attributes->get('messageId');
        $photoId = $messageId;
        // $photoId = $request->attributes->get('photoId');
        // $messageId = $request->attributes->get('message');
        try {
            $response = $this->photoService->getPhotoContent($messageId, $photoId);
        } catch (\InvalidArgumentException $exception) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        return $response;
    }

    /**
     * Add photo to message.
     *
     * @Route(name="kc.message.add_photo", path="/message/photo")
     * @Method("POST")
     * @ApiDoc(
     *     name="Add photo to Message",
     *     section="Chat Message",
     *     input={"class" = "Kulcua\Extension\Bundle\ChatBundle\Form\Type\MessagePhotoFormType", "name" = "message"}
     * )
     *
     * @param Request        $request
     * @param DomainMessage $message
     *
     * @return FosView
     *
     */
    public function addPhotoAction(Request $request, DomainMessage $message): FosView
    {
        $form = $this->get('form.factory')->createNamed('message', MessagePhotoFormType::class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            
            $messageId = new MessageId($this->get('broadway.uuid.generator')->generate());
            
            $this->get('broadway.command_handling.command_bus')->dispatch(
                new CreatePhotoMessage(
                    $messageId,
                    $data
                )
            );

            /** @var UploadedFile $photoMessage */
            $photoMessage = $form->getData()['photoMessage'];
            $this->commandBus->dispatch(MessagePhotoCommand::withData($photoMessage, $messageId));

            return $this->view(['messageId' => (string) $messageId]);
        }

        return $this->view($form->getErrors(), Response::HTTP_BAD_REQUEST);
    }
}
