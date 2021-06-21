<?php

namespace Kulcua\Extension\Bundle\MaintenanceBundle\DataFixtures\ORM;

use Broadway\CommandHandling\CommandBus;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use OpenLoyalty\Bundle\UserBundle\DataFixtures\ORM\LoadUserData;
use Kulcua\Extension\Component\Maintenance\Domain\Command\BookMaintenance;
use Kulcua\Extension\Component\Maintenance\Domain\MaintenanceId;
use Symfony\Bridge\Doctrine\Tests\Fixtures\ContainerAwareFixture;

/**
 * Class LoadMaintenanceData.
 */
class LoadMaintenanceData extends ContainerAwareFixture implements FixtureInterface, OrderedFixtureInterface
{
    const MAINTENANCE_ID = '00000000-0000-2222-0000-000000000000';
    const MAINTENANCE2_ID = '00000000-0000-2222-0000-000000000002';
    const MAINTENANCE3_ID = '00000000-0000-2222-0000-000000000003';
    const MAINTENANCE4_ID = '00000000-0000-2222-0000-000000000004';
    
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $phoneNumber = $faker->e164PhoneNumber;

        $maintenanceData = [
            'productSku' => '11111',
            'bookingDate' => (new \DateTime('+3 day'))->getTimestamp(),
            'bookingTime' => '9:00',
            'createdAt' => (new \DateTime('+1 day'))->getTimestamp(),
            'active' => false,
            'warrantyCenter' => 'HCM',
            'description' => 'no description',
            'cost' => '10.000',
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
            new BookMaintenance(
                new MaintenanceId(self::MAINTENANCE_ID),
                $maintenanceData,
                $customerData
            )
        );

        $maintenanceData['productSku'] = '22222';
        $maintenanceData['active'] = true;
        $maintenanceData['paymentStatus'] = 'unpaid';

        $bus->dispatch(
            new BookMaintenance(
                new MaintenanceId(self::MAINTENANCE2_ID),
                $maintenanceData,
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

        $maintenanceData['productSku'] = '333333';
        $maintenanceData['bookingTime'] = '10:00';
        $maintenanceData['warrantyCenter'] = 'Vung Tau';
        $maintenanceData['paymentStatus'] = 'unpaid';

        $bus->dispatch(
            new BookMaintenance(
                new MaintenanceId(self::MAINTENANCE4_ID),
                $maintenanceData,
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

        $maintenanceData['productSku'] = '44444';
        $maintenanceData['bookingTime'] = '10:30';
        $maintenanceData['warrantyCenter'] = 'Tra Vinh';
        $maintenanceData['paymentStatus'] = 'paid';

        $bus->dispatch(
            new BookMaintenance(
                new MaintenanceId(self::MAINTENANCE3_ID),
                $maintenanceData,
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