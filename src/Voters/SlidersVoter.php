<?php


namespace App\Voters;


use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SlidersVoter extends Voter
{
    public const VIEW_SLIDER = 'VIEW_SLIDER';
    public const ADD_SLIDER = 'ADD_SLIDER';
    public const EDIT_SLIDER = 'EDIT_SLIDER';
    public const DELETE_SLIDER = 'DELETE_SLIDER';
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

        if(self::VIEW_SLIDER == $attribute){
            return $this->voteOnView($user);
        }
        if(self::ADD_SLIDER == $attribute){
            return $this->voteOnAdd($user);
        }
        if(self::EDIT_SLIDER == $attribute){
            return $this->voteOnEdit($user);
        }
        if(self::DELETE_SLIDER == $attribute){
            return $this->voteOnDelete($user);
        }
    }

    private function voteOnView(User $user): bool
    {
        return in_array(self::VIEW_SLIDER, $user->getPermissions());
    }

    private function voteOnAdd(User $user): bool
    {
        return in_array(self::ADD_SLIDER, $user->getPermissions());
    }

    private function voteOnEdit(User $user): bool
    {
        return in_array(self::EDIT_SLIDER, $user->getPermissions());
    }

    private function voteOnDelete(User $user): bool
    {
        return in_array(self::DELETE_SLIDER, $user->getPermissions());
    }
}