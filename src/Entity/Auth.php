<?php


namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Dto\UserRegisterRequest;
use App\Dto\CheckCodeRequest;
use App\Dto\UserLoginRequest;
use App\Dto\UserWithTokenResponse;
use App\Dto\UserResponse;
use App\Dto\DriverInfoResponse;
use App\Dto\UpdateUserRequest;
#[ApiResource(
    collectionOperations: [
        "register"=> [
            "route_name"=>"auth.register",
            "method"=>"POST",
            "input"=>UserRegisterRequest::class,
            "status"=>201,
            "output"=>UserResponse::class
        ],
        "login"=> [
           "route_name"=>"auth.login",
            "method"=>"POST",
            "input"=>UserLoginRequest::class,
            "output"=>false,
            "status"=>200
        ],
        "logout"=>[
            "route_name" => "auth.logout",
            "method" => "DELETE",
            "status" => 204
        ],
       "token"=>[
           "route_name"=>"auth.checkcode",
           "method"=>"POST",
           "input"=>CheckCodeRequest::class,
           "output"=>UserWithTokenResponse::class,
           "status"=>200
       ],
        "user" => [
            "route_name" => "auth.user",
            "method" => "GET",
            "output" => UserResponse::class,
            "status" => 200
        ],
        "update_user"=>[
            "route_name"=>"user.update",
            "method"=>"POST",
            "input"=>UpdateUserRequest::class,
            "output"=>UserResponse::class,
            "status"=>200
        ],
       "driver" => [
           "route_name"=>"driver.info",
            "security"=>"is_granted('ROLE_DRIVER')",
            "method"=>"GET",
            "output"=>DriverInfoResponse::class,
            "status"=>200
       ],
    ],
    itemOperations: []
)]

class Auth
{

}