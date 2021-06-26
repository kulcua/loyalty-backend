<?php

declare(strict_types=1);

namespace Kulcua\Extension\Bundle\SuggestionBoxBundle\Controller\Api;

use Broadway\CommandHandling\CommandBus;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View as FosView;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Kulcua\Extension\Bundle\SuggestionBoxBundle\Form\Type\SuggestionBoxPhotoFormType;
use Kulcua\Extension\Bundle\SuggestionBoxBundle\Service\SuggestionBoxPhotoContentGeneratorInterface;
use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBox as DomainSuggestionBox;
use Kulcua\Extension\Component\SuggestionBox\Domain\Command\SuggestionBoxPhotoCommand;
use Kulcua\Extension\Component\SuggestionBox\Domain\Command\CreateSuggestionBox;
use Kulcua\Extension\Component\SuggestionBox\Domain\PhotoId;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBoxId;

/**
 * Class SuggestionBoxController.
 */
class SuggestionBoxController extends FOSRestController
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
     * @var SuggestionBoxPhotoContentGeneratorInterface
     */
    private $photoService;

    /**
     * SuggestionBoxController constructor.
     *
     * @param CommandBus                             $commandBus
     * @param FormFactory                            $formFactory
     * @param SuggestionBoxPhotoContentGeneratorInterface $photoService
     */
    public function __construct(
        CommandBus $commandBus,
        FormFactory $formFactory,
        SuggestionBoxPhotoContentGeneratorInterface $photoService
    ) {
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
        $this->photoService = $photoService;
    }

    /**
     * Get suggestionBox photo.
     *
     * @Route(name="kc.suggestion_box.get_photo", path="/photo/{suggestionBoxId}")
     * @Method("GET")
     * @ApiDoc(
     *     name="Get suggestionBox photo",
     *     section="SuggestionBox"
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getPhotoAction(Request $request): Response
    {
        $suggestionBoxId = $request->attributes->get('suggestionBoxId');
        $photoId = $suggestionBoxId;
        // $photoId = $request->attributes->get('photoId');
        // $suggestionBoxId = $request->attributes->get('suggestionBox');
        try {
            $response = $this->photoService->getPhotoContent($suggestionBoxId, $photoId);
        } catch (\InvalidArgumentException $exception) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        return $response;
    }

    /**
     * Add photo to suggestionBox.
     *
     * @Route(name="kc.suggestion_box.add_photo", path="/")
     * @Method("POST")
     * @ApiDoc(
     *     name="Add photo to SuggestionBox",
     *     section="SuggestionBox",
     *     input={"class" = "Kulcua\Extension\Bundle\SuggestionBoxBundle\Form\Type\SuggestionBoxPhotoFormType", "name" = "suggestionBox"}
     * )
     *
     * @param Request        $request
     * @param DomainSuggestionBox $suggestionBox
     *
     * @return FosView
     *
     */
    public function addPhotoAction(Request $request, DomainSuggestionBox $suggestionBox): FosView
    {
        $form = $this->get('form.factory')->createNamed('suggestionBox', SuggestionBoxPhotoFormType::class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            
            $suggestionBoxId = new SuggestionBoxId($this->get('broadway.uuid.generator')->generate());
            
            $this->get('broadway.command_handling.command_bus')->dispatch(
                new CreateSuggestionBox(
                    $suggestionBoxId,
                    $data
                )
            );

            /** @var UploadedFile $photo */
            $photo = $form->getData()['photo'];
            $this->commandBus->dispatch(SuggestionBoxPhotoCommand::withData($photo, $suggestionBoxId));

            return $this->view(['suggestionBoxId' => (string) $suggestionBoxId]);
        }

        return $this->view($form->getErrors(), Response::HTTP_BAD_REQUEST);
    }
}
