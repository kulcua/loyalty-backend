<?php

namespace Kulcua\Extension\Bundle\ChatBundle\Service;

use Kulcua\Extension\Component\Message\Domain\MessageId;
use Kulcua\Extension\Component\Message\Domain\PhotoId;
use Kulcua\Extension\Component\Message\Domain\Repository\MessagePhotoRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Gaufrette\Filesystem;

/**
 * Class MessagePhotoContentGenerator.
 */
class MessagePhotoContentGenerator implements MessagePhotoContentGeneratorInterface
{
    /**
     * @var MessagePhotoRepositoryInterface
     */
    private $photoRepository;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * MessagePhotoContentGenerator constructor.
     *
     * @param MessagePhotoRepositoryInterface $photoRepository
     * @param Filesystem                       $filesystem
     */
    public function __construct(MessagePhotoRepositoryInterface $photoRepository, Filesystem $filesystem)
    {
        $this->photoRepository = $photoRepository;
        $this->filesystem = $filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function getPhotoContent(string $messageId, string $photoId): Response
    {
        $photo = $this->photoRepository->findOneByIdMessageId(new PhotoId($photoId), new MessageId($messageId));
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
