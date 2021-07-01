<?php

namespace Kulcua\Extension\Bundle\SuggestionBoxBundle\Service;

use Kulcua\Extension\Component\SuggestionBox\Domain\SuggestionBoxId;
use Kulcua\Extension\Component\SuggestionBox\Domain\PhotoId;
use Kulcua\Extension\Component\SuggestionBox\Domain\Repository\SuggestionBoxPhotoRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Gaufrette\Filesystem;

/**
 * Class SuggestionBoxPhotoContentGenerator.
 */
class SuggestionBoxPhotoContentGenerator implements SuggestionBoxPhotoContentGeneratorInterface
{
    /**
     * @var SuggestionBoxPhotoRepositoryInterface
     */
    private $photoRepository;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * SuggestionBoxPhotoContentGenerator constructor.
     *
     * @param SuggestionBoxPhotoRepositoryInterface $photoRepository
     * @param Filesystem                       $filesystem
     */
    public function __construct(SuggestionBoxPhotoRepositoryInterface $photoRepository, Filesystem $filesystem)
    {
        $this->photoRepository = $photoRepository;
        $this->filesystem = $filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function getPhotoContent(string $suggestionBoxId, string $photoId): Response
    {
        $photo = $this->photoRepository->findOneByIdSuggestionBoxId(new PhotoId($photoId), new SuggestionBoxId($suggestionBoxId));
        if (null === $photo) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $content = $this->filesystem->get((string) $photo->getPath())->getContent();

        $response = new Response($content);
        $response->headers->set('Content-Disposition', 'inline');
        $response->headers->set('Content-Type', (string) $photo->getMimeType());
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');

        return $response;
    }
}
