<?php

namespace Kulcua\Extension\Bundle\WarrantyBundle\DataFixtures\ORM;

use Broadway\CommandHandling\CommandBus;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use OpenLoyalty\Bundle\UserBundle\DataFixtures\ORM\LoadUserData;
use Kulcua\Extension\Component\Warranty\Domain\Command\BookWarranty;
use Kulcua\Extension\Component\Warranty\Domain\WarrantyId;
use Symfony\Bridge\Doctrine\Tests\Fixtures\ContainerAwareFixture;

/**
 * Class LoadWarrantyData.
 */
class LoadWarrantyData extends ContainerAwareFixture implements FixtureInterface, OrderedFixtureInterface
{
    const MAINTENANCE_ID = '00000000-0000-3333-1111-000000000000';
    const MAINTENANCE2_ID = '00000000-0000-3333-1111-000000000002';
    const MAINTENANCE3_ID = '00000000-0000-3333-1111-000000000003';
    const MAINTENANCE4_ID = '00000000-0000-3333-1111-000000000004';
    
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $phoneNumber = $faker->e164PhoneNumber;

        $warrantyData = [
            'productSku' => 'DV1231',
            'bookingDate' => (new \DateTime('+3 day'))->getTimestamp(),
            'bookingTime' => '9:00',
            'createdAt' => (new \DateTime('+1 day'))->getTimestamp(),
            'active' => false,
            'warrantyCenter' => 'HCM',
            'description' => 'no description',
            'cost' => '500.000',
            'paymentStatus' => 'paid'
        ];

        /** @var CommandBus $bus */
        $bus = $this->container->get('broadway.command_handling.command_bus');
        $customerData = [
            'name' => 'John Doe',
            'email' => 'ol@oy.com',
            'nip' => 'aaa',
            'phone' => $phoneNumber,
            'loyaltyCardNumber' => '222',
            'address' => [
                'street' => 'Oxford Street',
                'address1' => '12',
                'city' => 'New York',
                'country' => 'US',
                'province' => 'New York',
                'postal' => '10001',
            ],
        ];

        $bus->dispatch(
            new BookWarranty(
                new WarrantyId(self::MAINTENANCE_ID),
                $warrantyData,
                $customerData
            )
        );

        $warrantyData['productSku'] = 'DD1231';
        $warrantyData['active'] = true;
        $warrantyData['cost'] = '22.000';
        $warrantyData['paymentStatus'] = 'unpaid';

        $bus->dispatch(
            new BookWarranty(
                new WarrantyId(self::MAINTENANCE2_ID),
                $warrantyData,
                [
                    'name' => 'John Doe',
                    'email' => 'open@oloy.com',
                    'nip' => 'aaa',
                    'phone' => $phoneNumber,
                    'loyaltyCardNumber' => 'sa2222',
                    'address' => [
                        'street' => 'Oxford Street',
                        'address1' => '12',
                        'city' => 'New York',
                        'country' => 'US',
                        'province' => 'New York',
                        'postal' => '10001',
                    ],
                ]
            )
        );

        $warrantyData['productSku'] = 'FW1231';
        $warrantyData['bookingTime'] = '10:00';
        $warrantyData['warrantyCenter'] = 'Vung Tau';
        $warrantyData['paymentStatus'] = 'unpaid';
        $warrantyData['cost'] = '223.000';

        $bus->dispatch(
            new BookWarranty(
                new WarrantyId(self::MAINTENANCE4_ID),
                $warrantyData,
                [
                    'name' => 'John Doe',
                    'email' => 'o@lo.com',
                    'nip' => 'aaa',
                    'phone' => $phoneNumber,
                    'loyaltyCardNumber' => 'sa21as222',
                    'address' => [
                        'street' => 'Oxford Street',
                        'address1' => '12',
                        'city' => 'New York',
                        'country' => 'US',
                        'province' => 'New York',
                        'postal' => '10001',
                    ],
                ]
            )
        );

        $warrantyData['productSku'] = 'VY3832';
        $warrantyData['bookingTime'] = '10:30';
        $warrantyData['warrantyCenter'] = 'Tra Vinh';
        $warrantyData['paymentStatus'] = 'paid';
        $warrantyData['cost'] = '100.000';

        $bus->dispatch(
            new BookWarranty(
                new WarrantyId(self::MAINTENANCE3_ID),
                $warrantyData,
                [
                    'name' => 'John Doe',
                    'email' => 'user@oloy.com',
                    'nip' => 'aaa',
                    'phone' => $phoneNumber,
                    'loyaltyCardNumber' => 'sa2222',
                    'address' => [
                        'street' => 'Oxford Street',
                        'address1' => '12',
                        'city' => 'New York',
                        'country' => 'US',
                        'province' => 'New York',
                        'postal' => '10001',
                    ],
                ]
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