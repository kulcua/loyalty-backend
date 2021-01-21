<?php

namespace Kulcua\Extension\Component\Maintenance\Domain\SystemEvent;

/**
 * Class MaintenanceSystemEvents.
 */
class MaintenanceSystemEvents
{
    const MAINTENANCE_BOOKED = 'kc.maintenance.booked';
    const MAINTENANCE_UPDATED = 'kc.maintenance.updated';
    const MAINTENANCE_DEACTIVATED = 'kc.maintenance.deactivated';
}