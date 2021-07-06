<?php


namespace App\Voters;


use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CategoryVoter extends Voter
{
    public const VIEW_CATEGORY = 'VIEW_CATEGORY';
    public const ADD_CATEGORY = 'ADD_CATEGORY';
    public const EDIT_CATEGORY = 'EDIT_CATEGORY';
    public const DELETE_CATEGORY = 'DELETE_CATEGORY';
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

        if(self::VIEW_CATEGORY == $attribute){
            return $this->voteOnView($user);
        }
        if(self::ADD_CATEGORY == $attribute){
            return $this->voteOnAdd($user);
        }
        if(self::EDIT_CATEGORY == $attribute){
            return $this->voteOnEdit($user);
        }
        if(self::DELETE_CATEGORY == $attribute){
            return $this->voteOnDelete($user);
        }
    }

    private function voteOnView(User $user): bool
    {
        return in_array(self::VIEW_CATEGORY, $user->getPermissions());
    }

    private function voteOnAdd(User $user): bool
    {
        return in_array(self::ADD_CATEGORY, $user->getPermissions());
    }

    private function voteOnEdit(User $user): bool
    {
        return in_array(self::EDIT_CATEGORY, $user->getPermissions());
    }

    private function voteOnDelete(User $user): bool
    {
        return in_array(self::DELETE_CATEGORY, $user->getPermissions());
    }
}