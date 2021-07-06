<?php


namespace App\Voters;


use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ComplaintsVoter extends Voter
{
    public const VIEW_COMPLAINT = 'VIEW_COMPLAINT';
    public const ADD_COMPLAINT = 'ADD_COMPLAINT';
    public const EDIT_COMPLAINT = 'EDIT_COMPLAINT';
    public const DELETE_COMPLAINT = 'DELETE_COMPLAINT';
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

        if(self::VIEW_COMPLAINT == $attribute){
            return $this->voteOnView($user);
        }
        if(self::ADD_COMPLAINT == $attribute){
            return $this->voteOnAdd($user);
        }
        if(self::EDIT_COMPLAINT == $attribute){
            return $this->voteOnEdit($user);
        }
        if(self::DELETE_COMPLAINT == $attribute){
            return $this->voteOnDelete($user);
        }
    }

    private function voteOnView(User $user): bool
    {
        return in_array(self::VIEW_COMPLAINT, $user->getPermissions());
    }

    private function voteOnAdd(User $user): bool
    {
        return in_array(self::ADD_COMPLAINT, $user->getPermissions());
    }

    private function voteOnEdit(User $user): bool
    {
        return in_array(self::EDIT_COMPLAINT, $user->getPermissions());
    }

    private function voteOnDelete(User $user): bool
    {
        return in_array(self::DELETE_COMPLAINT, $user->getPermissions());
    }
}