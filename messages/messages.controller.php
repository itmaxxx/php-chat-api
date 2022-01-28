<?php
  
  @include_once __DIR__ . "/../utils/httpException.php";
  @include_once __DIR__ . "/../utils/jsonResponse.php";
  @include_once __DIR__ . "/../chats/chats.service.php";
  @include_once __DIR__ . "/../users/users.service.php";
  @include_once __DIR__ . "./messages.service.php";
  @include_once __DIR__ . "/../locale/en/messages.php";
  @include_once __DIR__ . "/../utils/randomId.php";
  
  class MessagesController
  {
    private UsersService $usersService;
    private ChatsService $chatsService;
    private MessagesService $messagesService;
    
    function __construct($conn)
    {
      $this->chatsService = new ChatsService($conn);
      $this->usersService = new UsersService($conn);
      $this->messagesService = new MessagesService($conn);
    }
    
    function createMessage($req, $messageDto)
    {
      global $messages;
      
      try {
        preg_match("/\/api\/chats\/(?'chatId'[a-z0-9]+)\/messages/", $req['resource'], $parsedUrl);
  
        $chat = $this->chatsService->findById($parsedUrl["chatId"]);
  
        if (is_null($chat)) {
          httpException($messages["chat_not_found"], 404)['end']();
        }
  
        $initiatorParticipant = $this->chatsService->isUserChatParticipant($req["user"]["id"], $chat["id"]);
  
        if (!$initiatorParticipant)
        {
          httpException($messages["not_enough_permission"], 403)['end']();
        }
        
        $createdMessageId = randomId();
        
        $this->messagesService->createMessage($createdMessageId, $chat["id"], $req["user"]["id"], $messageDto["content"], $messageDto["contentType"]);
        
        $response = [
          "message" => $messages["message_sent"],
          "createdMessageId" => $createdMessageId
        ];
        
        jsonResponse($response, 201)['end']();
      }
      catch (PDOException $ex)
      {
        httpException($messages["failed_to_send_message_to_chat"])['end']();
      }
    }
    
    function getChatMessages($req)
    {
      global $messages;
      
      try {
        preg_match("/\/api\/chats\/(?'chatId'[a-z0-9]+)\/messages/", $req['resource'], $parsedUrl);
        
        $chat = $this->chatsService->findById($parsedUrl["chatId"]);
        
        if (is_null($chat)) {
          httpException($messages["chat_not_found"], 404)['end']();
        }
        
        $initiatorParticipant = $this->chatsService->isUserChatParticipant($req["user"]["id"], $chat["id"]);
        
        if (!$initiatorParticipant)
        {
          httpException($messages["not_enough_permission"], 403)['end']();
        }
        
        $chatMessages = $this->messagesService->getChatMessages($chat["id"]);
        
        $response = [
          "messages" => $chatMessages
        ];
        
        jsonResponse($response)['end']();
      }
      catch (PDOException $ex) {
        httpException($messages["failed_to_get_chat_messages"])['end']();
      }
    }
  }
