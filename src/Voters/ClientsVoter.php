<?php


namespace App\Voters;


use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ClientsVoter extends Voter
{
    public const VIEW_CLIENT = 'VIEW_CLIENT';
    public const ADD_CLIENT = 'ADD_CLIENT';
    public const EDIT_CLIENT = 'EDIT_CLIENT';
    public const DELETE_CLIENT = 'DELETE_CLIENT';
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

        if(self::VIEW_CLIENT == $attribute){
            return $this->voteOnView($user);
        }
        if(self::ADD_CLIENT == $attribute){
            return $this->voteOnAdd($user);
        }
        if(self::EDIT_CLIENT == $attribute){
            return $this->voteOnEdit($user);
        }
        if(self::DELETE_CLIENT == $attribute){
            return $this->voteOnDelete($user);
        }
    }

    private function voteOnView(User $user): bool
    {
        return in_array(self::VIEW_CLIENT, $user->getPermissions());
    }

    private function voteOnAdd(User $user): bool
    {
        return in_array(self::ADD_CLIENT, $user->getPermissions());
    }

    private function voteOnEdit(User $user): bool
    {
        return in_array(self::EDIT_CLIENT, $user->getPermissions());
    }

    private function voteOnDelete(User $user): bool
    {
        return in_array(self::DELETE_CLIENT, $user->getPermissions());
    }
}