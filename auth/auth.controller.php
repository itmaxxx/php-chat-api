<?php

@include_once __DIR__ . "/../utils/httpException.php";
@include_once __DIR__ . "/../utils/jsonResponse.php";
@include_once __DIR__ . "/auth.service.php";
@include_once __DIR__ . "/../utils/jwt.php";

class AuthController {
  private $authService;
  private $usersService;

  function __construct($conn) {
    $this->authService = new AuthService($conn);
    $this->usersService = new UsersService($conn);
  }

  function signUp($registerUserDto) {
    // TODO:
    // - check if user with this username exists
    // - encrypt password
    // - save user
    // + generate jwt
    
    $payload = array(
      "username" => $registerUserDto["username"],
      "createdAt" => time()
    );
    
    $jwt = jwtEncode($payload);
    
    $response = array(
      "jwt" => $jwt
    );
    
    return jsonResponse($response, 201);
  }

  function signIn($loginUserDto) {
    // TODO:
    // - find user by username
    // - check if password match
    // - generate jwt
  }
}
