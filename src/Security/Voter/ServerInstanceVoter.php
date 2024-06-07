<?php

namespace App\Security\Voter;

use App\Entity\ServerInstance;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ServerInstanceVoter extends Voter
{
    public const LIST = 'SERVER_INSTANCE_LIST';
    public const VIEW = 'SERVER_INSTANCE_VIEW';
    public const EDIT = 'SERVER_INSTANCE_EDIT';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if ($attribute === self::LIST) {
            return true;
        }

        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof \App\Entity\ServerInstance;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface || !$subject instanceof ServerInstance) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::LIST:
                //return $user->getRights()->has('canListServerInstances');
            case self::VIEW:
                return $subject->getStatus() !== 'deleted';
            case self::EDIT:
                // logic to determine if the user can EDIT
                // return true or false
                break;


        }

        return false;
    }
}
