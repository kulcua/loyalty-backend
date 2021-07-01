<?php

declare(strict_types=1);

namespace Kulcua\Extension\Bundle\SuggestionBoxBundle\Service;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class SuggestionBoxPhotoContentGenerator.
 */
interface SuggestionBoxPhotoContentGeneratorInterface
{
    /**
     * @param string $suggestionBoxId
     * @param string $photoId
     *
     * @return Response
     */
    public function getPhotoContent(string $suggestionBoxId, string $photoId): Response;
}
