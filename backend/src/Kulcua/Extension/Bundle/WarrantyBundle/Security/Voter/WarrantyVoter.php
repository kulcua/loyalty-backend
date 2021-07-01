<?php

namespace Kulcua\Extension\Bundle\WarrantyBundle\Security\Voter;

use OpenLoyalty\Bundle\UserBundle\Entity\User;
use OpenLoyalty\Bundle\UserBundle\Security\PermissionAccess;
use OpenLoyalty\Component\Customer\Domain\ReadModel\CustomerDetails;
use OpenLoyalty\Component\Seller\Domain\ReadModel\SellerDetailsRepository;
use Kulcua\Extension\Component\Warranty\Domain\ReadModel\WarrantyDetails;
use Kulcua\Extension\Component\Warranty\Domain\Warranty;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class WarrantyVoter.
 */
class WarrantyVoter extends Voter
{
    const PERMISSION_RESOURCE = 'WARRANTY';

    const LIST_WARRANTYS = 'LIST_WARRANTYS';
    const LIST_CURRENT_CUSTOMER_WARRANTYS = 'LIST_CURRENT_CUSTOMER_WARRANTYS';
    const VIEW = 'VIEW';
    const CREATE_WARRANTY = 'CREATE_WARRANTY';
    const EDIT_WARRANTY = 'EDIT_WARRANTY';
    const LIST_CUSTOMER_WARRANTYS = 'LIST_CUSTOMER_WARRANTYS';

    /**
     * @var SellerDetailsRepository
     */
    private $sellerDetailsRepository;

    /**
     * WarrantyVoter constructor.
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
            self::LIST_WARRANTYS, self::LIST_CURRENT_CUSTOMER_WARRANTYS, self::CREATE_WARRANTY, self::EDIT_WARRANTY
        ]) || $subject instanceof CustomerDetails && in_array($attribute, [
            self::LIST_CUSTOMER_WARRANTYS,
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
            case self::LIST_WARRANTYS:
                return $viewAdmin;
            case self::CREATE_WARRANTY:
                return $fullAdmin;
            case self::EDIT_WARRANTY:
                return $fullAdmin;
            case self::LIST_CURRENT_CUSTOMER_WARRANTYS:
                return $user->hasRole('ROLE_PARTICIPANT');
            case self::LIST_CUSTOMER_WARRANTYS:
                return $viewAdmin || $user->hasRole('ROLE_PARTICIPANT');
            case self::VIEW:
                return $viewAdmin || $this->canSellerOrCustomerView($user, $subject);
            default:
                return false;
        }
    }

    /**
     * @param User               $user
     * @param WarrantyDetails $subject
     *
     * @return bool
     */
    protected function canSellerOrCustomerView(User $user, WarrantyDetails $subject): bool
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
