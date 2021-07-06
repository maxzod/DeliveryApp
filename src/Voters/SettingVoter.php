<?php


namespace App\Voters;


use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SettingVoter extends Voter
{
    public const VIEW_SETTING = 'VIEW_SETTING';
    public const EDIT_SETTING = 'EDIT_SETTING';
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

        if(self::VIEW_SETTING == $attribute){
            return $this->voteOnView($user);
        }
        if(self::EDIT_SETTING == $attribute){
            return $this->voteOnEdit($user);
        }
    }

    private function voteOnView(User $user): bool
    {
        return in_array(self::VIEW_SETTING, $user->getPermissions());
    }


    private function voteOnEdit(User $user): bool
    {
        return in_array(self::EDIT_SETTING, $user->getPermissions());
    }

}