<h1>Chats e2e</h1>
<pre>
<?php
  
  @include_once __DIR__ . "/config.php";
  @include_once __DIR__ . "/lib/index.php";
  @include_once __DIR__ . "/../fixtures/chats.php";
  @include_once __DIR__ . "/../fixtures/users.php";
  @include_once __DIR__ . "/../locale/en/messages.php";
  @include_once __DIR__ . "/../vendor/autoload.php";
  @include_once __DIR__ . "/../utils/jwt.php";
  
  describe("[GET] /api/chats/:chatId", function () {
    it("should get private chat by id for chat participant", function () {
      global $testsConfig, $MaxDmitriev, $MaxAndIlyaChat;
      
      $jwt = signJwtForUser($MaxDmitriev);
      
      $response = request("GET", $testsConfig["host"] . "/api/chats/" . $MaxAndIlyaChat["id"], ["headers" => ["Authorization: Bearer $jwt"]]);
      
      $json = json_decode($response['data']);
      $chatData = $json->data->chat;
      
      assertStrict($response['info']['http_code'], 200);
      assertStrict($chatData->id, $MaxAndIlyaChat['id']);
      assertStrict($chatData->name, $MaxAndIlyaChat['name']);
      assertStrict(isset($chatData->isPrivate), false);
      assertStrict(isset($chatData->inviteLink), false);
    });
  
    it("should return error when trying to get private chat by id for NOT a chat participant", function () {
      global $testsConfig, $messages, $MatveyGorelik, $MaxAndIlyaChat;
    
      $jwt = signJwtForUser($MatveyGorelik);
    
      $response = request("GET", $testsConfig["host"] . "/api/chats/" . $MaxAndIlyaChat["id"], ["headers" => ["Authorization: Bearer $jwt"]]);
      $json = json_decode($response['data']);
    
      assertStrict($response['info']['http_code'], 401);
      assertStrict($json->data->error, $messages["no_access_to_the_chat"]);
    });
  
    it("should get public chat by id", function () {
      global $testsConfig, $MaxDmitriev, $GymPartyPublicChat;
      
      $jwt = signJwtForUser($MaxDmitriev);
    
      $response = request("GET", $testsConfig["host"] . "/api/chats/" . $GymPartyPublicChat["id"], ["headers" => ["Authorization: Bearer $jwt"]]);
    
      $json = json_decode($response['data']);
      $chatData = $json->data->chat;
    
      assertStrict($response['info']['http_code'], 200);
      assertStrict($chatData->id, $GymPartyPublicChat['id']);
      assertStrict($chatData->name, $GymPartyPublicChat['name']);
      assertStrict(isset($chatData->isPrivate), false);
      assertStrict(isset($chatData->inviteLink), false);
    });
  
    it("should return not authorized when trying to get chat by id without authorization", function () {
      global $testsConfig, $messages, $MaxAndIlyaChat;
    
      $response = request("GET", $testsConfig["host"] . "/api/chats/" . $MaxAndIlyaChat['id']);
      $json = json_decode($response['data']);
    
      assertStrict($response['info']['http_code'], 401);
      assertStrict($json->data->error, $messages["not_authenticated"]);
    });
  
    it("should return chat not found for deleted chat", function () {
      global $testsConfig, $MaxDmitriev, $messages, $DeletedChatWithMaxAndMatvey;
    
      $jwt = signJwtForUser($MaxDmitriev);
    
      $response = request("GET", $testsConfig["host"] . "/api/chats/" . $DeletedChatWithMaxAndMatvey["id"], ["headers" => ["Authorization: Bearer $jwt"]]);
      $json = json_decode($response['data']);
    
      assertStrict($response['info']['http_code'], 404);
      assertStrict($json->data->error, $messages["chat_not_found"]);
    });
    
    it("should return chat not found for random chat id", function () {
      global $testsConfig, $MaxDmitriev, $messages;
      
      $jwt = signJwtForUser($MaxDmitriev);
      
      $response = request("GET", $testsConfig["host"] . "/api/chats/random_id", ["headers" => ["Authorization: Bearer $jwt"]]);
      $json = json_decode($response['data']);
      
      assertStrict($response['info']['http_code'], 404);
      assertStrict($json->data->error, $messages["chat_not_found"]);
    });
  });
  
  describe("[POST] /api/chats", function () {
    it("should be able to create new chat", function () {
      global $testsConfig, $MaxDmitriev;
  
      $jwt = signJwtForUser($MaxDmitriev);
      
      $body = [
        "name" => "My private chat",
        "isPrivate" => true
      ];
      
      $response = request("POST", $testsConfig["host"] . "/api/chats", ["json" => $body, "headers" => ["Authorization: Bearer $jwt"]]);
      $json = json_decode($response['data']);
      $createdChat = $json->data->chat;
      
      assertStrict($response['info']['http_code'], 201);
      assertStrict($createdChat->name, $body["name"]);
    });
  });
  
  describe("[DELETE] /api/chats/:chatId", function () {
    it("should delete chat by chat admin", function () {
      global $testsConfig, $messages, $MaxDmitriev, $MaxAndIlyaChat;
      
      $jwt = signJwtForUser($MaxDmitriev);
      
      $response = request("DELETE", $testsConfig["host"] . "/api/chats/" . $MaxAndIlyaChat["id"], ["headers" => ["Authorization: Bearer $jwt"]]);
      $json = json_decode($response['data']);
      
      assertStrict($response['info']['http_code'], 200);
      assertStrict($json->data->message, $messages["chat_deleted"]);
    });
  
    it("should return not enough permission when trying to delete chat by a member", function () {
      global $testsConfig, $messages, $MatveyGorelik, $GymPartyPublicChat;
    
      $jwt = signJwtForUser($MatveyGorelik);
    
      $response = request("DELETE", $testsConfig["host"] . "/api/chats/" . $GymPartyPublicChat["id"], ["headers" => ["Authorization: Bearer $jwt"]]);
      $json = json_decode($response['data']);
    
      assertStrict($response['info']['http_code'], 403);
      assertStrict($json->data->error, $messages["not_enough_permission"]);
    });
  
    it("should return chat not found error for random chat id", function () {
      global $testsConfig, $messages, $MaxDmitriev;
    
      $jwt = signJwtForUser($MaxDmitriev);
    
      $response = request("DELETE", $testsConfig["host"] . "/api/chats/random_chat_id", ["headers" => ["Authorization: Bearer $jwt"]]);
      $json = json_decode($response['data']);
    
      assertStrict($response['info']['http_code'], 404);
      assertStrict($json->data->error, $messages["chat_not_found"]);
    });
  });
  
  describe("[DELETE] /api/chats/:chatId/users/:userId", function () {
    it("should delete chat participant by chat admin", function () {
      global $testsConfig, $messages, $MaxDmitriev, $MaxAndIlyaChat, $IlyaMehof;
      
      $jwt = signJwtForUser($MaxDmitriev);
      
      $response = request("DELETE", $testsConfig["host"] . "/api/chats/" . $MaxAndIlyaChat["id"] . "/users/" . $IlyaMehof["id"], ["headers" => ["Authorization: Bearer $jwt"]]);
      $json = json_decode($response['data']);
      
      assertStrict($response['info']['http_code'], 200);
      assertStrict($json->data->message, $messages["participant_deleted"]);
    });
    
    it("should return not enough permission when trying to delete chat by a member", function () {
      global $testsConfig, $messages, $MatveyGorelik, $GymPartyPublicChat, $IlyaMehof;
      
      $jwt = signJwtForUser($MatveyGorelik);
      
      $response = request("DELETE", $testsConfig["host"] . "/api/chats/" . $GymPartyPublicChat["id"] . "/users/" . $IlyaMehof["id"], ["headers" => ["Authorization: Bearer $jwt"]]);
      $json = json_decode($response['data']);
      
      assertStrict($response['info']['http_code'], 403);
      assertStrict($json->data->error, $messages["not_enough_permission"]);
    });
    
    it("should return chat not found error for random chat id", function () {
      global $testsConfig, $messages, $MaxDmitriev;
      
      $jwt = signJwtForUser($MaxDmitriev);
      
      $response = request("DELETE", $testsConfig["host"] . "/api/chats/random_chat_id/users/" . $MaxDmitriev["id"], ["headers" => ["Authorization: Bearer $jwt"]]);
      $json = json_decode($response['data']);
      
      assertStrict($response['info']['http_code'], 404);
      assertStrict($json->data->error, $messages["chat_not_found"]);
    });
  
    it("should return participant not found error for random user id", function () {
      global $testsConfig, $messages, $GymPartyPublicChat, $MaxDmitriev;
    
      $jwt = signJwtForUser($MaxDmitriev);
    
      $response = request("DELETE", $testsConfig["host"] . "/api/chats/" . $GymPartyPublicChat["id"] . "/users/random_id", ["headers" => ["Authorization: Bearer $jwt"]]);
      $json = json_decode($response['data']);
    
      assertStrict($response['info']['http_code'], 404);
      assertStrict($json->data->error, $messages["participant_not_found"]);
    });
  });
  
  describe("[GET] /api/chats/:chatId/users", function () {
    it("should get private chat participants by chat id for chat participant", function () {
      global $testsConfig, $MaxDmitriev, $MaxAndIlyaChat;
      
      $jwt = signJwtForUser($MaxDmitriev);
      
      $response = request("GET", $testsConfig["host"] . "/api/chats/" . $MaxAndIlyaChat["id"] . "/users", ["headers" => ["Authorization: Bearer $jwt"]]);
      
      $json = json_decode($response['data']);
      $chatData = $json->data->chat;
      
      assertStrict($response['info']['http_code'], 200);
      assertStrict($chatData->id, $MaxAndIlyaChat['id']);
      assertStrict($chatData->name, $MaxAndIlyaChat['name']);
      assertStrict(isset($chatData->isPrivate), false);
      assertStrict(isset($chatData->inviteLink), false);
    });
    
    it("should return error when trying to get private chat by id for NOT a chat participant", function () {
      global $testsConfig, $messages, $MatveyGorelik, $MaxAndIlyaChat;
      
      $jwt = signJwtForUser($MatveyGorelik);
      
      $response = request("GET", $testsConfig["host"] . "/api/chats/" . $MaxAndIlyaChat["id"] . "/users", ["headers" => ["Authorization: Bearer $jwt"]]);
      $json = json_decode($response['data']);
      
      assertStrict($response['info']['http_code'], 401);
      assertStrict($json->data->error, $messages["no_access_to_the_chat"]);
    });
    
    it("should get public chat participants by id", function () {
      global $testsConfig, $MaxDmitriev, $GymPartyPublicChat;
      
      $jwt = signJwtForUser($MaxDmitriev);
      
      $response = request("GET", $testsConfig["host"] . "/api/chats/" . $GymPartyPublicChat["id"] . "/users", ["headers" => ["Authorization: Bearer $jwt"]]);
      
      $json = json_decode($response['data']);
      $chatData = $json->data->chat;
      
      assertStrict($response['info']['http_code'], 200);
      assertStrict($chatData->id, $GymPartyPublicChat['id']);
      assertStrict($chatData->name, $GymPartyPublicChat['name']);
      assertStrict(isset($chatData->isPrivate), false);
      assertStrict(isset($chatData->inviteLink), false);
    });
    
    it("should return not authorized when trying to get chat by id without authorization", function () {
      global $testsConfig, $messages, $MaxAndIlyaChat;
      
      $response = request("GET", $testsConfig["host"] . "/api/chats/" . $MaxAndIlyaChat['id'] . "/users");
      $json = json_decode($response['data']);
      
      assertStrict($response['info']['http_code'], 401);
      assertStrict($json->data->error, $messages["not_authenticated"]);
    });
    
    it("should return chat not found for deleted chat", function () {
      global $testsConfig, $MaxDmitriev, $messages, $DeletedChatWithMaxAndMatvey;
      
      $jwt = signJwtForUser($MaxDmitriev);
      
      $response = request("GET", $testsConfig["host"] . "/api/chats/" . $DeletedChatWithMaxAndMatvey["id"] . "/users", ["headers" => ["Authorization: Bearer $jwt"]]);
      $json = json_decode($response['data']);
      
      assertStrict($response['info']['http_code'], 404);
      assertStrict($json->data->error, $messages["chat_not_found"]);
    });
    
    it("should return chat not found for random chat id", function () {
      global $testsConfig, $MaxDmitriev, $messages;
      
      $jwt = signJwtForUser($MaxDmitriev);
      
      $response = request("GET", $testsConfig["host"] . "/api/chats/random_id/users", ["headers" => ["Authorization: Bearer $jwt"]]);
      $json = json_decode($response['data']);
      
      assertStrict($response['info']['http_code'], 404);
      assertStrict($json->data->error, $messages["chat_not_found"]);
    });
  });
  
?>
</pre>