<?php

declare(strict_types=1);

namespace Kulcua\Extension\Bundle\SuggestionBoxBundle\Controller\Api;

use Broadway\CommandHandling\CommandBus;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View as FosView;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Kulcua\Extension\Bundle\SuggestionBoxBundle\Form\Type\SuggestionBoxFormType;
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
use Kulcua\Extension\Component\SuggestionBox\Domain\ReadModel\SuggestionBoxDetails;
use Kulcua\Extension\Component\SuggestionBox\Domain\Command\DeactivateSuggestionBox;
use FOS\RestBundle\View\View;
use Kulcua\Extension\Component\SuggestionBox\Domain\ReadModel\SuggestionBoxDetailsRepository;

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
     *     section="Suggestion Box"
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
     *     section="Suggestion Box",
     *     input={"class" = "Kulcua\Extension\Bundle\SuggestionBoxBundle\Form\Type\SuggestionBoxFormType", "name" = "suggestionBox"}
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
        $form = $this->get('form.factory')->createNamed('suggestionBox', SuggestionBoxFormType::class);
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

    /**
     * Method allows to update suggestion_box details.
     *
     * @param Request       $request
     * @param SuggestionBoxDetails $suggestion_box
     *
     * @return \FOS\RestBundle\View\View
     * @Route(name="kc.suggestion_box.edit", path="/suggestion_box/{suggestion_box}")
     * @Method("PUT")
     * @ApiDoc(
     *     name="Edit SuggestionBox",
     *     section="Suggestion Box",
     *     input={"class" = "Kulcua\Extension\Bundle\SuggestionBoxBundle\Form\Type\SuggestionBoxFormType", "name" = "suggestion_box"},
     *     statusCodes={
     *       200="Returned when successful",
     *       400="Returned when form contains errors",
     *     }
     * )
     */
    public function editSuggestionBoxAction(Request $request, SuggestionBoxDetails $suggestion_box)
    {
        $form = $this->get('form.factory')->createNamed('suggestion_box', SuggestionBoxFormType::class, [], [
            'method' => 'PUT',
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($this->get('kc.suggestion_box.form_handler.edit')->onSuccess($suggestion_box->getSuggestionBoxId(), $form) === true) {
                return $this->view([
                    'suggestionBoxId' => (string) $suggestion_box->getSuggestionBoxId(),
                ]);
            } else {
                return $this->view($form->getErrors(), Response::HTTP_BAD_REQUEST);
            }
        }

        return $this->view($form->getErrors(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * Change suggestion_box state action to active or inactive.
     *
     * @Route(name="kc.suggestion_box.change_state", path="/suggestion_box/{suggestion_box}/{active}", requirements={"active":"active|inactive"})
     * @Method("POST")
     * @ApiDoc(
     *     name="Change SuggestionBox state active",
     *     section="Suggestion Box"
     * )
     *
     * @param SuggestionBoxDetails $suggestion_box
     * @param                    $active
     *
     * @return View
     */
    public function DeactivateAction(SuggestionBoxDetails $suggestion_box, $active) : View
    {
        if ('active' === $active) {
            $suggestion_box->setActive(true);
        } elseif ('inactive' === $active) {
            $suggestion_box->setActive(false);
        }

        $this->get('broadway.command_handling.command_bus')->dispatch(
            new DeactivateSuggestionBox($suggestion_box->getSuggestionBoxId(), $suggestion_box->isActive())
        );

        return $this->view(['suggestionBoxId' => (string) $suggestion_box->getSuggestionBoxId()]);
    }

    /**
     * Method will return complete list of all suggestionBoxs.
     *
     * @Route(name="kc.suggestionBox.list", path="/suggestion_box")
     * @Method("GET")
     *
     * @ApiDoc(
     *     name="get suggestionBoxs list",
     *     section="Suggestion Box")
     *
     * @param Request      $request
     * @param ParamFetcher $paramFetcher
     *
     * @return View
     *
     */
    public function listAction(Request $request, ParamFetcher $paramFetcher): View
    {
        $params = $this->get('oloy.user.param_manager')->stripNulls($paramFetcher->all());

        $pagination = $this->get('oloy.pagination')->handleFromRequest($request, 'timestamp', 'DESC');

        /** @var SuggestionBoxDetailsRepository $repo */
        $repo = $this->get(SuggestionBoxDetailsRepository::class);

        $suggestionBoxs = $repo->findByParametersPaginated(
            $params,
            false,
            $pagination->getPage(),
            $pagination->getPerPage(),
            $pagination->getSort(),
            $pagination->getSortDirection()
        );
        $total = $repo->countTotal($params, $request->get('strict', false));;

        return $this->view([
            'suggestionBoxs' => $suggestionBoxs,
            'total' => $total,
        ], 200);
    }
}
