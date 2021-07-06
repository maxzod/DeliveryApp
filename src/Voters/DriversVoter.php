<?php


namespace App\Voters;


use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class DriversVoter extends Voter
{
    public const VIEW_DRIVER = 'VIEW_DRIVER';
    public const ADD_DRIVER = 'ADD_DRIVER';
    public const EDIT_DRIVER = 'EDIT_DRIVER';
    public const DELETE_DRIVER = 'DELETE_DRIVER';
    public const NOTIFY_DRIVER = 'NOTIFY_DRIVER';
    protected function supports(string $attribute, $subject): bool
    {
        if (null === $attribute) {
            return false;
        }

        return \defined('self::'.$attribute);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if(!$user instanceof User){
            return false;
        }
        if(in_array('ROLE_ADMIN', $user->getRoles())) return true;

        if(self::VIEW_DRIVER == $attribute){
            return $this->voteOnView($user);
        }
        if(self::ADD_DRIVER == $attribute){
            return $this->voteOnAdd($user);
        }
        if(self::EDIT_DRIVER == $attribute){
            return $this->voteOnEdit($user);
        }
        if(self::DELETE_DRIVER == $attribute){
            return $this->voteOnDelete($user);
        }
        if(self::NOTIFY_DRIVER == $attribute){
            return $this->voteOnNotify($user);
        }
    }

    private function voteOnView(User $user): bool
    {
        return in_array(self::VIEW_DRIVER, $user->getPermissions());
    }

    private function voteOnAdd(User $user): bool
    {
        return in_array(self::ADD_DRIVER, $user->getPermissions());
    }

    private function voteOnEdit(User $user): bool
    {
        return in_array(self::EDIT_DRIVER, $user->getPermissions());
    }

    private function voteOnDelete(User $user): bool
    {
        return in_array(self::DELETE_DRIVER, $user->getPermissions());
    }

    private function voteOnNotify(User $user)
    {
        return in_array(self::NOTIFY_DRIVER, $user->getPermissions());
    }
}