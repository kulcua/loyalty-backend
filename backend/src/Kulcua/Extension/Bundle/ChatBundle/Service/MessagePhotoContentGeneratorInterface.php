<?php

declare(strict_types=1);

namespace Kulcua\Extension\Bundle\ChatBundle\Service;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class MessagePhotoContentGenerator.
 */
interface MessagePhotoContentGeneratorInterface
{
    /**
     * @param string $messageId
     * @param string $photoId
     *
     * @return Response
     */
    public function getPhotoContent(string $messageId, string $photoId): Response;
}
