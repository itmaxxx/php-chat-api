<h1>Messages e2e</h1>
<pre>
<?php
  
  @include_once __DIR__ . "/config.php";
  @include_once __DIR__ . "/lib/index.php";
  @include_once __DIR__ . "/../fixtures/chats.php";
  @include_once __DIR__ . "/../fixtures/users.php";
  @include_once __DIR__ . "/../locale/en/messages.php";
  @include_once __DIR__ . "/../vendor/autoload.php";
  @include_once __DIR__ . "/../utils/jwt.php";
  
  function getChatMessages($chatId, $user = null): array
  {
    global $testsConfig;
    
    $jwt = '';
      
    if (!is_null($user))
    {
      $jwt = signJwtForUser($user);
    }
  
    $response = request(
            "GET",
            $testsConfig["host"] . "/api/chats/" . $chatId . "/messages",
            !is_null($user) ? ["headers" => ["Authorization: Bearer $jwt"]] : []
    );
  
    return [
      $response,
      json_decode($response['data'])
    ];
  }
  
  function sendMessageToChat($chatId, $user = null): array
  {
    global $testsConfig;
    
    $jwt = '';
    
    if (!is_null($user))
    {
      $jwt = signJwtForUser($user);
    }
  
    $body = [
      "content" => "Hello world",
      "contentType" => 0
    ];
    
    $response = request(
            "POST",
            $testsConfig["host"] . "/api/chats/" . $chatId . "/messages",
            ["json" => $body, "headers" => !is_null($user) ? ["Authorization: Bearer $jwt"] : []]
    );
    
    return [
      $response,
      json_decode($response['data'])
    ];
  }
  
  describe("[GET] /api/chats/:chatId/messages", function () {
    it("should get private chat messages for chat participant", function () {
      global $MaxDmitriev, $MaxAndIlyaChat;
      
      [$response, $json] = getChatMessages($MaxAndIlyaChat["id"], $MaxDmitriev);
      
      assertStrict($response['info']['http_code'], 200);
      assertStrict(count($json->data->messages), 4);
    });
    
    it("should return error when trying to get private chat messages for NOT a chat participant", function () {
      global $messages, $MatveyGorelik, $MaxAndIlyaChat;
  
      [$response, $json] = getChatMessages($MaxAndIlyaChat["id"], $MatveyGorelik);
      
      assertStrict($response['info']['http_code'], 403);
      assertStrict($json->data->error, $messages["not_enough_permission"]);
    });
    
    it("should get public chat messages", function () {
      global $MaxDmitriev, $GymPartyPublicChat;
      
      [$response, $json] = getChatMessages($GymPartyPublicChat["id"], $MaxDmitriev);
      
      assertStrict($response['info']['http_code'], 200);
      assertStrict(count($json->data->messages), 2);
    });
    
    it("should return not authorized when trying to get chat messages without authorization", function () {
      global $messages, $MaxAndIlyaChat;
      
      [$response, $json] = getChatMessages($MaxAndIlyaChat["id"]);
      
      assertStrict($response['info']['http_code'], 401);
      assertStrict($json->data->error, $messages["not_authenticated"]);
    });
    
    it("should return chat not found for deleted chat", function () {
      global $MaxDmitriev, $messages, $DeletedChatWithMaxAndMatvey;
      
      [$response, $json] = getChatMessages($DeletedChatWithMaxAndMatvey["id"], $MaxDmitriev);
      
      assertStrict($response['info']['http_code'], 404);
      assertStrict($json->data->error, $messages["chat_not_found"]);
    });
    
    it("should return chat not found for random chat id", function () {
      global $MaxDmitriev, $messages;
      
      [$response, $json] = getChatMessages("randomid", $MaxDmitriev);
      
      assertStrict($response['info']['http_code'], 404);
      assertStrict($json->data->error, $messages["chat_not_found"]);
    });
  });
  
  describe("[POST] /api/chats/:chatId/messages", function () {
    it("should send message to private chat for chat participant", function () {
      global $MaxDmitriev, $MaxAndIlyaChat, $messages;
    
      [$response, $json] = sendMessageToChat($MaxAndIlyaChat["id"], $MaxDmitriev);
      
      assertStrict($response['info']['http_code'], 201);
      assertStrict($json->data->message, $messages["message_sent"]);
    });
  
    it("should return error when trying to send message to private chat for NOT a chat participant", function () {
      global $messages, $MatveyGorelik, $MaxAndIlyaChat;
      
      [$response, $json] = sendMessageToChat($MaxAndIlyaChat["id"], $MatveyGorelik);
    
      assertStrict($response['info']['http_code'], 403);
      assertStrict($json->data->error, $messages["not_enough_permission"]);
    });
  
    it("should return error when trying to send message to public chat for NOT a chat participant", function () {
      global $messages, $MatveyGorelik, $GymPartyPublicChat;
    
      [$response, $json] = sendMessageToChat($GymPartyPublicChat["id"], $MatveyGorelik);
    
      assertStrict($response['info']['http_code'], 403);
      assertStrict($json->data->error, $messages["not_enough_permission"]);
    });
  
    it("should return not authorized when trying to send message without authorization", function () {
      global $messages, $MaxAndIlyaChat;
    
      [$response, $json] = sendMessageToChat($MaxAndIlyaChat["id"]);
    
      assertStrict($response['info']['http_code'], 401);
      assertStrict($json->data->error, $messages["not_authenticated"]);
    });
  
    it("should return chat not found for deleted chat", function () {
      global $MaxDmitriev, $messages, $DeletedChatWithMaxAndMatvey;
    
      [$response, $json] = sendMessageToChat($DeletedChatWithMaxAndMatvey["id"], $MaxDmitriev);
    
      assertStrict($response['info']['http_code'], 404);
      assertStrict($json->data->error, $messages["chat_not_found"]);
    });
  
    it("should return chat not found for random chat id", function () {
      global $MaxDmitriev, $messages;
  
      [$response, $json] = sendMessageToChat("randomid", $MaxDmitriev);
    
      assertStrict($response['info']['http_code'], 404);
      assertStrict($json->data->error, $messages["chat_not_found"]);
    });
  });

?>
