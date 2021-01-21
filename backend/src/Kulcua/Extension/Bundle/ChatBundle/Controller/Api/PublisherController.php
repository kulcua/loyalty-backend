<?php

namespace Kulcua\Extension\Bundle\ChatBundle\Controller\Api;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;

class PublisherController
{
    public function __invoke(PublisherInterface $publisher): Response
    {
        $update = new Update(
            'http://example.com/books/1',
            json_encode(['status' => 'OutOfStock']),
            true // private
        );

        // Publisher's JWT must contain this topic, a URI template it matches or * in mercure.publish or you'll get a 401
        // Subscriber's JWT must contain this topic, a URI template it matches or * in mercure.subscribe to receive the update
        $publisher($update);

        return new Response('private update published!');
    }
}
