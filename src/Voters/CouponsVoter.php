<?php


namespace App\Voters;


use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CouponsVoter extends Voter
{
    public const VIEW_COUPON = 'VIEW_COUPON';
    public const ADD_COUPON = 'ADD_COUPON';
    public const EDIT_COUPON = 'EDIT_COUPON';
    public const DELETE_COUPON = 'DELETE_COUPON';
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

        if(self::VIEW_COUPON == $attribute){
            return $this->voteOnView($user);
        }
        if(self::ADD_COUPON == $attribute){
            return $this->voteOnAdd($user);
        }
        if(self::EDIT_COUPON == $attribute){
            return $this->voteOnEdit($user);
        }
        if(self::DELETE_COUPON == $attribute){
            return $this->voteOnDelete($user);
        }
    }

    private function voteOnView(User $user): bool
    {
        return in_array(self::VIEW_COUPON, $user->getPermissions());
    }

    private function voteOnAdd(User $user): bool
    {
        return in_array(self::ADD_COUPON, $user->getPermissions());
    }

    private function voteOnEdit(User $user): bool
    {
        return in_array(self::EDIT_COUPON, $user->getPermissions());
    }

    private function voteOnDelete(User $user): bool
    {
        return in_array(self::DELETE_COUPON, $user->getPermissions());
    }
}