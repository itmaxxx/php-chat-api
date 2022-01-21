<?php
  
  @include_once __DIR__ . "/../utils/httpException.php";
  @include_once __DIR__ . "/../utils/jsonResponse.php";
  @include_once __DIR__ . "/users.service.php";
  @include_once __DIR__ . "../chats/chats.service.php";
  @include_once __DIR__ . "/../locale/en/messages.php";
  
  class UsersController
  {
    private $usersService;
    private $chatsService;
    
    function __construct($conn)
    {
      $this->usersService = new UsersService($conn);
      $this->chatsService = new ChatsService($conn);
    }
    
    function getUsers()
    {
      $users = $this->usersService->getUsers();
      
      $response = [
        "users" => $users
      ];
      
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
      
      $response = [
        "user" => $this->usersService->createUserRO($user)
      ];
      
      jsonResponse($response)['end']();
    }
    
    function createUser($userDto)
    {
      global $messages;

      $result = $this->usersService->createUser($userDto);
      
      if (!$result) {
        httpException($messages["failed_to_create_user"])['end']();
      }
      
      $response = [
        "message" => $messages["user_created"],
        "user" => $result
      ];
      
      jsonResponse($response)['end']();
    }
    
    function getMe($req)
    {
      $response = ["user" => $this->usersService->createUserRO($req["user"])];
      
      jsonResponse($response);
    }
    
    function getUserChats($req)
    {
      $chats = $this->chatsService->getUserChats($req["user"]["id"]);
      
      $response = ["chats" => $chats];
      
      jsonResponse($response);
    }
  }
