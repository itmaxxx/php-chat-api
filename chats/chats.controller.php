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
      $this->chatsService = new ChatsService($conn);
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

      $chat = $this->chatsService->findById($chatId);

      if (is_null($chat)) {
        httpException($messages["chat_not_found"], 404)['end']();
      }
      
      if ($chat["isPrivate"] && !$this->chatsService->isUserChatParticipant($req["user"]["id"], $chatId)) {
        httpException($messages["no_access_to_the_chat"], 401)['end']();
      }

      $response = [
        "chat" => $this->chatsService->createChatRO($chat)
      ];
      
      jsonResponse($response)['end']();
    }
    
    function createChat($chatDto)
    {
      global $messages;

      $result = $this->chatsService->createChat($chatDto);

      if (!$result) {
        httpException($messages["failed_to_create_chat"])['end']();
      }

      $response = [
        "message" => $messages["chat_created"],
        "chat" => $result
      ];
      
      jsonResponse($response)['end']();
    }
  }
