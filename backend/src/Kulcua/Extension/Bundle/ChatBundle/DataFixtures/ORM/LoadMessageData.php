<?php

namespace Kulcua\Extension\Bundle\ChatBundle\DataFixtures\ORM;

use Broadway\CommandHandling\CommandBus;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use OpenLoyalty\Bundle\UserBundle\DataFixtures\ORM\LoadUserData;
use Kulcua\Extension\Component\Message\Domain\Command\CreateMessage;
use Kulcua\Extension\Component\Message\Domain\MessageId;
use Symfony\Bridge\Doctrine\Tests\Fixtures\ContainerAwareFixture;

/**
 * Class LoadMessageData.
 */
class LoadMessageData extends ContainerAwareFixture implements FixtureInterface, OrderedFixtureInterface
{
    const MESSAGE_ID = '00000000-0000-4444-0000-000000000000';
    const MESSAGE2_ID = '00000000-0000-4444-0000-000000000002';
    const MESSAGE3_ID = '00000000-0000-4444-0000-000000000003';
    const MESSAGE4_ID = '00000000-0000-4444-0000-000000000004';

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $messageData = [
            'conversationId' => '00000000-0000-3333-0000-000000000002',
            'conversationParticipantIds' => ['22200000-0000-474c-b092-b0dd880c07e2', '00000000-0000-474c-b092-b0dd880c07e1'],
            'senderId' => '00000000-0000-474c-b092-b0dd880c07e1',
            'senderName' => 'John Doe',
            'message' => 'hello admin, im john',
            'messageTimestamp' => (new \DateTime('+1 day'))->getTimestamp(),
        ];

        /** @var CommandBus $bus */
        $bus = $this->container->get('broadway.command_handling.command_bus');

        $bus->dispatch(
            new CreateMessage(
                new MessageId(self::MESSAGE_ID),
                $messageData
            )
        );

        $messageData['message'] = 'reply me, plz';
        $bus->dispatch(
            new CreateMessage(
                new MessageId(self::MESSAGE2_ID),
                $messageData
            )
        );

        $messageData['senderId'] = '22200000-0000-474c-b092-b0dd880c07e2';
        $messageData['senderName'] = 'admin';
        $messageData['message'] = 'hi Jane, im admin';
        $bus->dispatch(
            new CreateMessage(
                new MessageId(self::MESSAGE3_ID),
                $messageData
            )
        );

        $messageData['senderId'] = '00000000-0000-474c-b092-b0dd880c07e1';
        $messageData['senderName'] = 'John Doe';
        $messageData['message'] = 'bye admin';

        $bus->dispatch(
            new CreateMessage(
                new MessageId(self::MESSAGE4_ID),
                $messageData
            )
        );
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder()
    {
        return 3;
    }
}