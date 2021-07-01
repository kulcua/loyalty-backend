<?php

namespace Kulcua\Extension\Bundle\SuggestionBoxBundle\Security\Voter;

use OpenLoyalty\Bundle\UserBundle\Entity\User;
use OpenLoyalty\Bundle\UserBundle\Security\PermissionAccess;
use OpenLoyalty\Component\Customer\Domain\ReadModel\CustomerDetails;
use OpenLoyalty\Component\Seller\Domain\ReadModel\SellerDetailsRepository;
use Kulcua\Extension\Component\SuggestionBox\Domain\ReadModel\SuggestionBoxDetails;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class SuggestionBoxVoter.
 */
class SuggestionBoxVoter extends Voter
{
    const PERMISSION_RESOURCE = 'SUGGESTION_BOX';

    const LIST_SUGGESTION_BOXS = 'LIST_SUGGESTION_BOXS';
    const LIST_CURRENT_CUSTOMER_SUGGESTION_BOXS = 'LIST_CURRENT_CUSTOMER_SUGGESTION_BOXS';
    const VIEW = 'VIEW';
    const CREATE_SUGGESTION_BOX = 'CREATE_SUGGESTION_BOX';
    const EDIT_SUGGESTION_BOX = 'EDIT_SUGGESTION_BOX';
    const LIST_CUSTOMER_SUGGESTION_BOXS = 'LIST_CUSTOMER_SUGGESTION_BOXS';

    /**
     * @var SellerDetailsRepository
     */
    private $sellerDetailsRepository;

    /**
     * SuggestionBoxVoter constructor.
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
            self::LIST_SUGGESTION_BOXS, self::LIST_CURRENT_CUSTOMER_SUGGESTION_BOXS, self::CREATE_SUGGESTION_BOX, self::EDIT_SUGGESTION_BOX
        ]) || $subject instanceof CustomerDetails && in_array($attribute, [
            self::LIST_CUSTOMER_SUGGESTION_BOXS,
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
            case self::LIST_SUGGESTION_BOXS:
                return $viewAdmin;
            case self::CREATE_SUGGESTION_BOX:
                return $fullAdmin;
            case self::EDIT_SUGGESTION_BOX:
                return $fullAdmin;
            case self::LIST_CURRENT_CUSTOMER_SUGGESTION_BOXS:
                return $user->hasRole('ROLE_PARTICIPANT');
            case self::LIST_CUSTOMER_SUGGESTION_BOXS:
                return $viewAdmin || $user->hasRole('ROLE_PARTICIPANT');
            case self::VIEW:
                return $viewAdmin || $this->canSellerOrCustomerView($user, $subject);
            default:
                return false;
        }
    }

    /**
     * @param User               $user
     * @param SuggestionBoxDetails $subject
     *
     * @return bool
     */
    protected function canSellerOrCustomerView(User $user, SuggestionBoxDetails $subject): bool
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
