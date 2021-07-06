<?php


namespace App\Voters;


use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class OrdersVoter extends Voter
{
    public const VIEW_ORDER = 'VIEW_ORDER';
    public const ADD_ORDER = 'ADD_ORDER';
    public const EDIT_ORDER = 'EDIT_ORDER';
    public const DELETE_ORDER = 'DELETE_ORDER';
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

        if(self::VIEW_ORDER == $attribute){
            return $this->voteOnView($user);
        }
        if(self::ADD_ORDER == $attribute){
            return $this->voteOnAdd($user);
        }
        if(self::EDIT_ORDER == $attribute){
            return $this->voteOnEdit($user);
        }
        if(self::DELETE_ORDER == $attribute){
            return $this->voteOnDelete($user);
        }
    }

    private function voteOnView(User $user): bool
    {
        return in_array(self::VIEW_ORDER, $user->getPermissions());
    }

    private function voteOnAdd(User $user): bool
    {
        return in_array(self::ADD_ORDER, $user->getPermissions());
    }

    private function voteOnEdit(User $user): bool
    {
        return in_array(self::EDIT_ORDER, $user->getPermissions());
    }

    private function voteOnDelete(User $user): bool
    {
        return in_array(self::DELETE_ORDER, $user->getPermissions());
    }
}