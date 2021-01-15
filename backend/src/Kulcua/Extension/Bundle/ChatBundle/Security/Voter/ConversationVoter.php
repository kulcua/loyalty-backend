<?php

namespace Kulcua\Extension\Bundle\ChatBundle\Security\Voter;

use OpenLoyalty\Bundle\UserBundle\Entity\User;
use OpenLoyalty\Bundle\UserBundle\Security\PermissionAccess;
use OpenLoyalty\Component\Customer\Domain\ReadModel\CustomerDetails;
use OpenLoyalty\Component\Seller\Domain\ReadModel\SellerDetailsRepository;
use Kulcua\Extension\Component\Conversation\Domain\ReadModel\ConversationDetails;
use Kulcua\Extension\Component\Conversation\Domain\Conversation;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class ConversationVoter.
 */
class ConversationVoter extends Voter
{
    const PERMISSION_RESOURCE = 'CONVERSATION';

    const LIST_CONVERSATIONS = 'LIST_CONVERSATIONS';
    const LIST_CURRENT_CUSTOMER_CONVERSATIONS = 'LIST_CURRENT_CUSTOMER_CONVERSATIONS';
    const VIEW = 'VIEW';
    const CREATE_CONVERSATION = 'CREATE_CONVERSATION';
    const LIST_CUSTOMER_CONVERSATIONS = 'LIST_CUSTOMER_CONVERSATIONS';

    /**
     * @var SellerDetailsRepository
     */
    private $sellerDetailsRepository;

    /**
     * ConversationVoter constructor.
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
            self::LIST_CONVERSATIONS, self::LIST_CURRENT_CUSTOMER_CONVERSATIONS, self::CREATE_CONVERSATION, self::EDIT_CONVERSATION_LABELS,
        ]) || $subject instanceof CustomerDetails && in_array($attribute, [
            self::LIST_CUSTOMER_CONVERSATIONS,
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
            case self::LIST_CONVERSATIONS:
                return $viewAdmin;
            case self::CREATE_CONVERSATION:
                return $fullAdmin;
            case self::EDIT_CONVERSATION:
                return $fullAdmin;
            case self::LIST_CURRENT_CUSTOMER_CONVERSATIONS:
                return $user->hasRole('ROLE_PARTICIPANT');
            case self::LIST_CUSTOMER_CONVERSATIONS:
                return $viewAdmin || $user->hasRole('ROLE_PARTICIPANT');
            case self::VIEW:
                return $viewAdmin || $this->canSellerOrCustomerView($user, $subject);
            default:
                return false;
        }
    }

    /**
     * @param User               $user
     * @param ConversationDetails $subject
     *
     * @return bool
     */
    protected function canSellerOrCustomerView(User $user, ConversationDetails $subject): bool
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
