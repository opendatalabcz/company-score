<?php

namespace App\Security;

use App\Entity\Account;
use App\Entity\Firm;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class FirmVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE ='delete';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::DELETE])) {
            return false;
        }

        if (!is_int($subject)) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if ($this->security->isGranted('ROLE_ADMIN')){
            return true;
        }

        $account = $token->getUser();
        if (!$account instanceof Account) {
            return false;
        }

        $firm = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($firm,$account);
            case self::EDIT:
                return $this->canEdit($firm,$account);
            case self::DELETE:
                return $this->canDelete($firm,$account);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canDelete( $firm, Account $account)
    {
        return true;
    }
    private function canView( $firm, Account $account)
    {
        // if they can edit, they can view
        if ($this->canEdit($firm, $account)) {
            return true;
        }

        return false;
    }

    private function canEdit( $firm, Account $account): bool
    {
        // this assumes that the data object has a getOwner() method
        // to get the entity of the user who owns this data object
        if($firm === $account->getId())
            return true;
        return false;
    }

}
