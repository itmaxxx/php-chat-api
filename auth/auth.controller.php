<?php
  
  @include_once __DIR__ . "/../utils/httpException.php";
  @include_once __DIR__ . "/../utils/jsonResponse.php";
  @include_once __DIR__ . "/auth.service.php";
  @include_once __DIR__ . "/../utils/jwt.php";
  @include_once __DIR__ . "/../locale/en/messages.php";
  
  class AuthController
  {
    private $authService;
    private $usersService;
    
    function __construct($conn)
    {
      $this->authService = new AuthService($conn);
      $this->usersService = new UsersService($conn);
    }
    
    function signUp($registerUserDto): array
    {
      global $messages;
      
      // TODO:
      // - encrypt password
      
      $foundUser = $this->usersService->getUserByUsername($registerUserDto["username"]);
      
      if (!is_null($foundUser)) {
        httpException($messages["username_taken"])['end']();
      }
      
      $id = random_int(0, 9999999);
      
      try {
        $this->usersService->createUser($id, $registerUserDto["username"], $registerUserDto["password"]);
      } catch (Exception $exception) {
        httpException($messages["failed_to_sign_up"])['end']();
      }
      
      $jwtPayload = array(
        "username" => $registerUserDto["username"],
        "createdAt" => time()
      );
      
      $jwt = jwtEncode($jwtPayload);
      
      $response = array(
        "jwt" => $jwt
      );
      
      return jsonResponse($response);
    }
    
    function signIn($loginUserDto): array
    {
      global $messages;
      
      // TODO:
      // - check encrypted password match
      
      $foundUser = $this->usersService->getUserByUsername($loginUserDto["username"]);
      
      if (is_null($foundUser)) {
        httpException($messages["user_not_found"])['end']();
      }
      
      if ($foundUser["password"] !== $loginUserDto["password"]) {
        httpException($messages["failed_to_sign_in"], 401)['end']();
      }
      
      $jwtPayload = array(
        "username" => $foundUser["username"],
        "createdAt" => time()
      );
      
      $jwt = jwtEncode($jwtPayload);
      
      $response = array(
        "jwt" => $jwt
      );
      
      return jsonResponse($response);
    }
  }
