<?php

namespace Kulcua\Extension\Bundle\ChatBundle\DataFixtures\ORM;

use Broadway\CommandHandling\CommandBus;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use OpenLoyalty\Bundle\UserBundle\DataFixtures\ORM\LoadUserData;
use Kulcua\Extension\Component\Conversation\Domain\Command\CreateConversation;
use Kulcua\Extension\Component\Conversation\Domain\ConversationId;
use Symfony\Bridge\Doctrine\Tests\Fixtures\ContainerAwareFixture;

/**
 * Class LoadConversationData.
 */
class LoadConversationData extends ContainerAwareFixture implements FixtureInterface, OrderedFixtureInterface
{
    const CONVERSATION_ID = '00000000-0000-3333-0000-000000000000';
    const CONVERSATION2_ID = '00000000-0000-3333-0000-000000000002';
    const CONVERSATION3_ID = '00000000-0000-3333-0000-000000000003';

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $phoneNumber = $faker->e164PhoneNumber;

        $conversationData = [
            'participantIds' => ['22200000-0000-474c-b092-b0dd880c07e2', '00000000-0000-474c-b092-b0dd880c07e2'],
            'participantNames' => ['admin', 'Jane Doe'],
            'lastMessageSnippet' => 'hello admin, jane',
            'lastMessageTimestamp' => (new \DateTime('+1 day'))->getTimestamp(),
        ];

        /** @var CommandBus $bus */
        $bus = $this->container->get('broadway.command_handling.command_bus');

        $bus->dispatch(
            new CreateConversation(
                new ConversationId(self::CONVERSATION_ID),
                $conversationData
            )
        );

        $conversationData['participantIds'] = ['22200000-0000-474c-b092-b0dd880c07e2', '00000000-0000-474c-b092-b0dd880c07e1'];
        $conversationData[participantNames] = ['admin', 'John Doe'];
        $conversationData['lastMessageSnippet'] = 'john doe nek';

        $bus->dispatch(
            new CreateConversation(
                new ConversationId(self::CONVERSATION2_ID),
                $conversationData
            )
        );

        $conversationData['participantIds'] = ['22200000-0000-474c-b092-b0dd880c07e2', '11111111-0000-474c-b092-b0dd880c07e1'];
        $conversationData[participantNames] = ['admin', 'John1 Doe1'];
        $conversationData['lastMessageSnippet'] = 'john1 doe1 hello world';
        $bus->dispatch(
            new CreateConversation(
                new ConversationId(self::CONVERSATION3_ID),
                $conversationData
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
        return 2;
    }
}