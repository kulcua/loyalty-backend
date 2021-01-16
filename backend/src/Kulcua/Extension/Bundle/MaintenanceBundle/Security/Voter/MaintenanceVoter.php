<?php

namespace Kulcua\Extension\Bundle\MaintenanceBundle\Security\Voter;

use OpenLoyalty\Bundle\UserBundle\Entity\User;
use OpenLoyalty\Bundle\UserBundle\Security\PermissionAccess;
use OpenLoyalty\Component\Customer\Domain\ReadModel\CustomerDetails;
use OpenLoyalty\Component\Seller\Domain\ReadModel\SellerDetailsRepository;
use Kulcua\Extension\Component\Maintenance\Domain\ReadModel\MaintenanceDetails;
use Kulcua\Extension\Component\Maintenance\Domain\Maintenance;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class MaintenanceVoter.
 */
class MaintenanceVoter extends Voter
{
    const PERMISSION_RESOURCE = 'MAINTENANCE';

    const LIST_MAINTENANCES = 'LIST_MAINTENANCES';
    const LIST_CURRENT_CUSTOMER_MAINTENANCES = 'LIST_CURRENT_CUSTOMER_MAINTENANCES';
    const VIEW = 'VIEW';
    const CREATE_MAINTENANCE = 'CREATE_MAINTENANCE';
    const LIST_CUSTOMER_MAINTENANCES = 'LIST_CUSTOMER_MAINTENANCES';

    /**
     * @var SellerDetailsRepository
     */
    private $sellerDetailsRepository;

    /**
     * MaintenanceVoter constructor.
     *
     * @param SellerDetailsRepository $sellerDetailsRepository
     */
    public function __construct(SellerDetailsRepository $sellerDetailsRepository)
    {
        $this->sellerDetailsRepository = $sellerDetailsRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($attribute, $subject)
    {
        return $subject == null && in_array($attribute, [
            self::LIST_MAINTENANCES, self::LIST_CURRENT_CUSTOMER_MAINTENANCES, self::CREATE_MAINTENANCE
        ]) || $subject instanceof CustomerDetails && in_array($attribute, [
            self::LIST_CUSTOMER_MAINTENANCES,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var User $user */
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        $viewAdmin = $user->hasRole('ROLE_ADMIN')
                     && $user->hasPermission(self::PERMISSION_RESOURCE, [PermissionAccess::VIEW]);

        $fullAdmin = $user->hasRole('ROLE_ADMIN')
                     && $user->hasPermission(self::PERMISSION_RESOURCE, [PermissionAccess::VIEW, PermissionAccess::MODIFY]);

        switch ($attribute) {
            case self::LIST_MAINTENANCES:
                return $viewAdmin;
            case self::CREATE_MAINTENANCE:
                return $fullAdmin;
            case self::EDIT_MAINTENANCE:
                return $fullAdmin;
            case self::LIST_CURRENT_CUSTOMER_MAINTENANCES:
                return $user->hasRole('ROLE_PARTICIPANT');
            case self::LIST_CUSTOMER_MAINTENANCES:
                return $viewAdmin || $user->hasRole('ROLE_PARTICIPANT');
            case self::VIEW:
                return $viewAdmin || $this->canSellerOrCustomerView($user, $subject);
            default:
                return false;
        }
    }

    /**
     * @param User               $user
     * @param MaintenanceDetails $subject
     *
     * @return bool
     */
    protected function canSellerOrCustomerView(User $user, MaintenanceDetails $subject): bool
    {
        if ($user->hasRole('ROLE_SELLER')) {
            return true;
        }

        //get email instead of id
        if ($user->hasRole('ROLE_PARTICIPANT') && $subject->getCustomerData()->getEmail() && (string) $subject->getCustomerData()->getEmail() == $user->getEmail()) {
            return true;
        }

        return false;
    }
}
