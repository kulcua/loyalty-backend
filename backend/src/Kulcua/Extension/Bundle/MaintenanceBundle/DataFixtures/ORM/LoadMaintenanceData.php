<?php

namespace Kulcua\Extension\Bundle\MaintenanceBundle\DataFixtures\ORM;

use Broadway\CommandHandling\CommandBus;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
// use OpenLoyalty\Bundle\PosBundle\DataFixtures\ORM\LoadPosData;
use OpenLoyalty\Bundle\UserBundle\DataFixtures\ORM\LoadUserData;
use Kulcua\Extension\Component\Maintenance\Domain\Command\BookMaintenance;
// use OpenLoyalty\Component\Maintenance\Domain\PosId;
use OpenLoyalty\Component\Maintenance\Domain\MaintenanceId;
use Symfony\Bridge\Doctrine\Tests\Fixtures\ContainerAwareFixture;

/**
 * Class LoadMaintenanceData.
 */
class LoadMaintenanceData extends ContainerAwareFixture implements FixtureInterface, OrderedFixtureInterface
{
    const MAINTENANCE_ID = '00000000-0000-1111-0000-000000000000';
    // const MAINTENANCE_COUPONS_ID = '00000000-0000-1111-0000-000000002121';
    // const MAINTENANCE_COUPONS_USED_ID = '00000000-0000-1111-0000-000000002123';
    const MAINTENANCE2_ID = '00000000-0000-1111-0000-000000000002';
    const MAINTENANCE3_ID = '00000000-0000-1111-0000-000000000003';
    const MAINTENANCE4_ID = '00000000-0000-1111-0000-000000000004';
    // const MAINTENANCE5_ID = '00000000-0000-1111-0000-000000000005';
    // const MAINTENANCE6_ID = '00000000-0000-1111-0000-000000000006';
    // const MAINTENANCE7_ID = '00000000-0000-1111-0000-000000000007';
    // const MAINTENANCE8_ID = '00000000-0000-1111-0000-000000000008';
    // const MAINTENANCE9_ID = '00000000-0000-1111-0000-000000000009';

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $phoneNumber = $faker->e164PhoneNumber;

        $maintenanceData = [
            'productSku' => '11111',
            'bookingDate' => (new \DateTime('+1 day'))->getTimestamp(),
            'createdAt' => (new \DateTime('+3 day'))->getTimestamp(),
            'active' => false,
            'warrantyCenter' => 'HCM',
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
        $maintenanceData['warrantyCenter'] = 'Vung Tau';
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
        $maintenanceData['warrantyCenter'] = 'Tra Vinh';
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

        // $this->loadMaintenanceForCouponUsage($bus);
        // $this->loadMaintenanceReturn($bus);
    }

    // /**
    //  * @param CommandBus $bus
    //  */
    // private function loadMaintenanceReturn(CommandBus $bus): void
    // {
    //     $maintenanceData = [
    //         'documentNumber' => '20181101',
    //         'purchasePlace' => 'New York',
    //         'purchaseDate' => (new \DateTime('+1 day'))->getTimestamp(),
    //         'documentType' => 'sell',
    //     ];
    //     $items = [
    //         [
    //             'sku' => ['code' => 'SKU1'],
    //             'name' => 'item 1',
    //             'quantity' => 1,
    //             'grossValue' => 1,
    //             'category' => 'aaa',
    //             'maker' => 'sss',
    //             'labels' => [
    //                 [
    //                     'key' => 'test',
    //                     'value' => 'label',
    //                 ],
    //                 [
    //                     'key' => 'test',
    //                     'value' => 'label2',
    //                 ],
    //             ],
    //         ],
    //         [
    //             'sku' => ['code' => 'SKU2'],
    //             'name' => 'item 2',
    //             'quantity' => 2,
    //             'grossValue' => 2,
    //             'category' => 'bbb',
    //             'maker' => 'ccc',
    //         ],
    //     ];

    //     /** @var CommandBus $bus */
    //     $customerData = [
    //         'name' => 'John Doe',
    //         'email' => 'ol@oy.com',
    //         'nip' => 'aaa',
    //         'phone' => '',
    //         'loyaltyCardNumber' => '222',
    //         'address' => [
    //             'street' => 'Oxford Street',
    //             'address1' => '12',
    //             'city' => 'New York',
    //             'country' => 'US',
    //             'province' => 'New York',
    //             'postal' => '10001',
    //         ],
    //     ];

    //     $bus->dispatch(
    //         new RegisterMaintenance(
    //             new MaintenanceId(self::MAINTENANCE8_ID),
    //             $maintenanceData,
    //             $customerData,
    //             $items,
    //             new PosId(LoadPosData::POS_ID),
    //             null,
    //             null,
    //             null,
    //             null,
    //             [
    //                 ['key' => 'scan_id', 'value' => 'abc123789def-abc123789def-abc123789def-abc123789def'],
    //             ]
    //         )
    //     );

    //     $maintenanceData2 = [
    //         'documentNumber' => '201811011023',
    //         'purchasePlace' => 'New York',
    //         'purchaseDate' => (new \DateTime('+1 day'))->getTimestamp(),
    //         'documentType' => 'return',
    //     ];

    //     $bus->dispatch(
    //         new RegisterMaintenance(
    //             new MaintenanceId(self::MAINTENANCE9_ID),
    //             $maintenanceData2,
    //             $customerData,
    //             $items,
    //             new PosId(LoadPosData::POS_ID),
    //             null,
    //             null,
    //             null,
    //             '20181101',
    //             [
    //                 ['key' => 'scan_id', 'value' => 'abc123789def-abc123789def-abc123789def-abc123789def'],
    //             ]
    //         )
    //     );
    // }

    // /**
    //  * @param CommandBus $bus
    //  */
    // private function loadMaintenanceForCouponUsage(CommandBus $bus): void
    // {
    //     $maintenanceData = [
    //             'documentNumber' => '12355-coupons',
    //             'documentType' => 'sell',
    //             'purchaseDate' => (new \DateTime('2015-01-01'))->getTimestamp(),
    //             'purchasePlace' => 'New York',
    //     ];
    //     $items = [
    //             0 => [
    //                 'sku' => ['code' => '123'],
    //                 'name' => 'sku',
    //                 'quantity' => 1,
    //                 'grossValue' => 20,
    //                 'category' => 'test',
    //                 'maker' => 'company',
    //             ],
    //             1 => [
    //                 'sku' => ['code' => '1123'],
    //                 'name' => 'sku',
    //                 'quantity' => 1,
    //                 'grossValue' => 100,
    //                 'category' => 'test',
    //                 'maker' => 'company',
    //             ],
    //         ];
    //     $customerData = [
    //             'name' => 'John Doe',
    //             'email' => LoadUserData::USER_COUPON_RETURN_USERNAME,
    //             'nip' => 'aaa',
    //             'phone' => '+48123123000',
    //             'loyaltyCardNumber' => 'not-present-in-system',
    //             'address' => [
    //                 'street' => 'Oxford Street',
    //                 'address1' => '12',
    //                 'city' => 'New York',
    //                 'country' => 'US',
    //                 'province' => 'New York',
    //                 'postal' => '10001',
    //             ],
    //     ];

    //     $bus->dispatch(
    //         new RegisterMaintenance(
    //             new MaintenanceId(self::MAINTENANCE_COUPONS_USED_ID),
    //             $maintenanceData,
    //             $customerData,
    //             $items,
    //             null,
    //             null,
    //             null,
    //             null,
    //             null,
    //             []
    //         )
    //     );
    // }

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