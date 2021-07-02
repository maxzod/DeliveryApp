<?php


namespace App\Dto;


class UserWithTokenResponse
{
    public string $token;
    public UserResponse $user;
}