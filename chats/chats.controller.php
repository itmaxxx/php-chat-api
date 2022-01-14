<?php
  
  @include_once __DIR__ . "/../utils/httpException.php";
  @include_once __DIR__ . "/../utils/jsonResponse.php";
  @include_once __DIR__ . "/chats.service.php";
  @include_once __DIR__ . "/../locale/en/messages.php";
  
  class ChatsController
  {
    private $chatsService;
    
    function __construct($conn)
    {
      $this->chatsService = new UsersService($conn);
    }
    
    function getChats()
    {
      $chats = $this->chatsService->getChats();
      
      $response = [
        "chats" => $chats
      ];
      
      jsonResponse($response)['end']();
    }
    
    function getChatById($req)
    {
      global $messages;
      
      # Parse chat id from url
      $chatId = intval(substr($req['resource'], strlen('/api/chats/')));
      
      $response = [
        "chat" => []
      ];
      
      jsonResponse($response)['end']();
    }
    
    function createChat($chatDto)
    {
      $response = [
        "message" => "Chat created",
        "chat" => []
      ];
      
      jsonResponse($response)['end']();
    }
  }
