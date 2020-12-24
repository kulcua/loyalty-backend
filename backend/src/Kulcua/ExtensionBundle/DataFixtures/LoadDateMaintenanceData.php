<?php

namespace Kulcua\ExtensionBundle\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Kulcua\ExtensionBundle\Entity\DateMaintenance;

class LoadDateMaintenaceData implements FixtureInterface
{
    // const DATE_MAINTENANCE_ID = 1;
    // const DATE_MAINTENANCE_PRODUCT_SKU = '10000001';
    // const DATE_MAINTENANCE_ORDER_DATE = NOW();
    // const DATE_MAINTENANCE_STATUS = true;
    // const DATE_MAINTENANCE_CREATED_AT = NOW();
    const DATE_MAINTENANCE_USER_ID = '22200000-0000-474c-b092-b0dd880c07e2';

    // const DATE_MAINTENANCE_1_PRODUCT_SKU = '10000002';
    // const DATE_MAINTENANCE_1_ORDER_DATE = NOW();
    // const DATE_MAINTENANCE_1_STATUS = true;
    // const DATE_MAINTENANCE_1_CREATED_AT = NOW();
    // const DATE_MAINTENANCE_1_USER_ID = '22200000-0000-474c-b092-b0dd880c07e2';

    // const DATE_MAINTENANCE_2_PRODUCT_SKU = '10000003';
    // const DATE_MAINTENANCE_2_ORDER_DATE = NOW();
    // const DATE_MAINTENANCE_2_STATUS = true;
    // const DATE_MAINTENANCE_2_CREATED_AT = NOW();
    // const DATE_MAINTENANCE_2_USER_ID = 'e21682f9-9ffc-4227-b4d6-ae7b41519f02';

    // const DATE_MAINTENANCE_3_PRODUCT_SKU = '10000004';
    // const DATE_MAINTENANCE_3_ORDER_DATE = NOW();
    // const DATE_MAINTENANCE_3_STATUS = true;
    // const DATE_MAINTENANCE_3_CREATED_AT = NOW();
    // const DATE_MAINTENANCE_3_USER_ID = 'e21682f9-9ffc-4227-b4d6-ae7b41519f03';

    public function load(ObjectManager $manager)
    {
        $date_maintenance1 = new DateMaintenance();
        $date_maintenance1->setProductSku('10000001');
        $date_maintenance1->setOrderDate(date_default_timezone_get());
        $date_maintenance1->setStatus(false);
        $date_maintenance1->setCreatedAt(date_default_timezone_get());
        $date_maintenance1->setUser(self::DATE_MAINTENANCE_USER_ID);
        $manager->persist($date_maintenance1);

        // $date_maintenance2 = new DateMaintenace();
        // $date_maintenance2.setProductSku('10000002');
        // $date_maintenance2.setOrderDate(NOW());
        // $date_maintenance2.setStatus(false);
        // $date_maintenance2.setCreatedAt(NOW());
        // $date_maintenance2.setUser('22200000-0000-474c-b092-b0dd880c07e2');
        // $manager->persist($date_maintenance2);

        // $date_maintenance3 = new DateMaintenace();
        // $date_maintenance3.setProductSku('10000003');
        // $date_maintenance3.setOrderDate(NOW());
        // $date_maintenance3.setStatus(false);
        // $date_maintenance3.setCreatedAt(NOW());
        // $date_maintenance3.setUser('e21682f9-9ffc-4227-b4d6-ae7b41519f02');
        // $manager->persist($date_maintenance3);

        // $date_maintenance4 = new DateMaintenace();
        // $date_maintenance4.setProductSku('10000004');
        // $date_maintenance4.setOrderDate(NOW());
        // $date_maintenance4.setStatus(false);
        // $date_maintenance4.setCreatedAt(NOW());
        // $date_maintenance4.setUser('e21682f9-9ffc-4227-b4d6-ae7b41519f03');
        // $manager->persist($date_maintenance4);

        $manager->flush();
    }
}