<?php

namespace Brangerieau\SymfonyCmsBundle\Security\Voter;

use Brangerieau\SymfonyCmsBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileVoter extends Voter
{
    public const EDIT = 'PROFILE_EDIT';
    public const VIEW = 'PROFILE_VIEW';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        return match ($attribute) {
            self::EDIT => $this->canEdit($subject, $user),
            self::VIEW => $this->canView($subject, $user),
            default => false,
        };
    }

    private function canEdit(User $user, User $user_connected): bool
    {
        return $user === $user_connected;
    }

    private function canView(User $user, User $user_connected): bool
    {
        return $user === $user_connected || in_array('ROLE_SUPER_ADMIN', $user_connected->getRoles());
    }
}
