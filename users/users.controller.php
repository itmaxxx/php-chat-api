<?php
  
  @include_once __DIR__ . "/../utils/httpException.php";
  @include_once __DIR__ . "/../utils/jsonResponse.php";
  @include_once __DIR__ . "/users.service.php";
  @include_once __DIR__ . "/../locale/en/messages.php";
  
  class UsersController
  {
    private $usersService;
    
    function __construct($conn)
    {
      $this->usersService = new UsersService($conn);
    }
    
    function getUsers()
    {
      $users = $this->usersService->getUsers();
      
      $response = array(
        "users" => $users
      );
      
      jsonResponse($response)['end']();
    }
    
    function getUserById($req)
    {
      global $messages;
      
      # Parse user id from url
      $userId = intval(substr($req['resource'], strlen('/api/users/')));
      
      $user = $this->usersService->getUserById($userId);
      
      if (is_null($user)) {
        httpException($messages["user_not_found"], 404)['end']();
      }
      
      $response = array(
        "user" => $this->usersService->createUserRO($user)
      );
      
      jsonResponse($response)['end']();
    }
    
    function createUser($userDto)
    {
      $result = $this->usersService->createUser($userDto);
      
      if (!$result) {
        httpException("Failed to create user")['end']();
      }
      
      $response = array(
        "message" => "User created",
        "user" => $result
      );
      
      jsonResponse($response)['end']();
    }
    
    function getMe($bearer) {
      var_dump($bearer);
    }
  }
