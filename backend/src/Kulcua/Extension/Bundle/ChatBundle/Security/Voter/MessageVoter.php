<?php

namespace Kulcua\Extension\Bundle\ChatBundle\Security\Voter;

use OpenLoyalty\Bundle\UserBundle\Entity\User;
use OpenLoyalty\Bundle\UserBundle\Security\PermissionAccess;
use OpenLoyalty\Component\Customer\Domain\ReadModel\CustomerDetails;
use OpenLoyalty\Component\Seller\Domain\ReadModel\SellerDetailsRepository;
use Kulcua\Extension\Component\Message\Domain\ReadModel\MessageDetails;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class MessageVoter.
 */
class MessageVoter extends Voter
{
    const PERMISSION_RESOURCE = 'MESSAGE';

    const LIST_MESSAGES = 'LIST_MESSAGES';
    const VIEW = 'VIEW';
    const CREATE_MESSAGE = 'CREATE_MESSAGE';
    const LIST_CUSTOMER_MESSAGES = 'LIST_CUSTOMER_MESSAGES';

    /**
     * @var SellerDetailsRepository
     */
    private $sellerDetailsRepository;

    /**
     * MessageVoter constructor.
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
            self::LIST_MESSAGES, self::CREATE_MESSAGE
        ]) || $subject instanceof CustomerDetails && in_array($attribute, [
            self::LIST_CUSTOMER_MESSAGES,
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
            case self::LIST_MESSAGES:
                return $viewAdmin;
            case self::CREATE_MESSAGE:
                return $fullAdmin;
            case self::EDIT_MESSAGE:
                return $fullAdmin;
            case self::LIST_CURRENT_CUSTOMER_MESSAGES:
                return $user->hasRole('ROLE_PARTICIPANT');
            case self::LIST_CUSTOMER_MESSAGES:
                return $viewAdmin || $user->hasRole('ROLE_PARTICIPANT');
            case self::VIEW:
                return $viewAdmin || $this->canSellerOrCustomerView($user, $subject);
            default:
                return false;
        }
    }

    /**
     * @param User               $user
     * @param MessageDetails $subject
     *
     * @return bool
     */
    protected function canSellerOrCustomerView(User $user, MessageDetails $subject): bool
    {
        if ($user->hasRole('ROLE_SELLER')) {
            return true;
        }

        if ($user->hasRole('ROLE_PARTICIPANT') && $subject->getCustomerData()->getId() && (string) $subject->getCustomerData()->getId() == $user->getId()) {
            return true;
        }

        return false;
    }
}
