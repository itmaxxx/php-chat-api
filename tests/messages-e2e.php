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
  
  describe("[GET] /api/chats/:chatId/messages", function () {
    it("should get private chat messages for chat participant", function () {
      global $testsConfig, $MaxDmitriev, $MaxAndIlyaChat;
      
      $jwt = signJwtForUser($MaxDmitriev);
      
      $response = request("POST", $testsConfig["host"] . "/api/chats/" . $MaxAndIlyaChat["id"] . "/messages", ["headers" => ["Authorization: Bearer $jwt"]]);
      
      $json = json_decode($response['data']);
      
      assertStrict($response['info']['http_code'], 200);
    });
    
    it("should return error when trying to get private chat messages for NOT a chat participant", function () {
      global $testsConfig, $messages, $MatveyGorelik, $MaxAndIlyaChat;
      
      $jwt = signJwtForUser($MatveyGorelik);
      
      $response = request("POST", $testsConfig["host"] . "/api/chats/" . $MaxAndIlyaChat["id"] . "/messages", ["headers" => ["Authorization: Bearer $jwt"]]);
      $json = json_decode($response['data']);
      
      assertStrict($response['info']['http_code'], 403);
      assertStrict($json->data->error, $messages["not_enough_permission"]);
    });
    
    it("should get public chat messages by id", function () {
      global $testsConfig, $MaxDmitriev, $GymPartyPublicChat;
      
      $jwt = signJwtForUser($MaxDmitriev);
      
      $response = request("POST", $testsConfig["host"] . "/api/chats/" . $GymPartyPublicChat["id"] . "/messages", ["headers" => ["Authorization: Bearer $jwt"]]);
      
      $json = json_decode($response['data']);
      
      assertStrict($response['info']['http_code'], 200);
    });
    
    it("should return not authorized when trying to get chat messages without authorization", function () {
      global $testsConfig, $messages, $MaxAndIlyaChat;
      
      $response = request("POST", $testsConfig["host"] . "/api/chats/" . $MaxAndIlyaChat['id'] . "/messages");
      $json = json_decode($response['data']);
      
      assertStrict($response['info']['http_code'], 401);
      assertStrict($json->data->error, $messages["not_authenticated"]);
    });
    
    it("should return chat not found for deleted chat", function () {
      global $testsConfig, $MaxDmitriev, $messages, $DeletedChatWithMaxAndMatvey;
      
      $jwt = signJwtForUser($MaxDmitriev);
      
      $response = request("POST", $testsConfig["host"] . "/api/chats/" . $DeletedChatWithMaxAndMatvey["id"] . "/messages", ["headers" => ["Authorization: Bearer $jwt"]]);
      $json = json_decode($response['data']);
      
      assertStrict($response['info']['http_code'], 404);
      assertStrict($json->data->error, $messages["chat_not_found"]);
    });
    
    it("should return chat not found for random chat id", function () {
      global $testsConfig, $MaxDmitriev, $messages;
      
      $jwt = signJwtForUser($MaxDmitriev);
      
      $response = request("POST", $testsConfig["host"] . "/api/chats/randomid/messages", ["headers" => ["Authorization: Bearer $jwt"]]);
      $json = json_decode($response['data']);
      
      assertStrict($response['info']['http_code'], 404);
      assertStrict($json->data->error, $messages["chat_not_found"]);
    });
  });
  
  describe("[POST] /api/chats/:chatId/messages", function () {
//    it("should return not authorized error when trying to send message without authorization", function () {
//      global $testsConfig, $messages, $GymPartyPublicChat;
//
//      $body = [
//        "message" => "Hello world"
//      ];
//
//      $response = request("POST", $testsConfig["host"] . "/api/chats/" . $GymPartyPublicChat[id], ["json" => $body]);
//      $json = json_decode($response['data']);
//
//      assertStrict($response['info']['http_code'], 401);
//      assertStrict($json->data->error, $messages["not_authenticated"]);
//    });
  
    it("should send message to private chat for chat participant", function () {
      global $testsConfig, $MaxDmitriev, $MaxAndIlyaChat;
    
      $jwt = signJwtForUser($MaxDmitriev);
    
      $response = request("POST", $testsConfig["host"] . "/api/chats/" . $MaxAndIlyaChat["id"] . "/messages", ["headers" => ["Authorization: Bearer $jwt"]]);
      $json = json_decode($response['data']);
    
      assertStrict($response['info']['http_code'], 200);
    });
  
    it("should return error when trying to send message to private chat for NOT a chat participant", function () {
      global $testsConfig, $messages, $MatveyGorelik, $MaxAndIlyaChat;
    
      $jwt = signJwtForUser($MatveyGorelik);
    
      $response = request("POST", $testsConfig["host"] . "/api/chats/" . $MaxAndIlyaChat["id"] . "/messages", ["headers" => ["Authorization: Bearer $jwt"]]);
      $json = json_decode($response['data']);
    
      assertStrict($response['info']['http_code'], 403);
      assertStrict($json->data->error, $messages["not_enough_permission"]);
    });
  
    it("should return error when trying to send message to public chat for NOT a chat participant", function () {
      global $testsConfig, $messages, $MaxDmitriev, $GymPartyPublicChat;
    
      $jwt = signJwtForUser($MaxDmitriev);
    
      $response = request("POST", $testsConfig["host"] . "/api/chats/" . $GymPartyPublicChat["id"] . "/messages", ["headers" => ["Authorization: Bearer $jwt"]]);
      $json = json_decode($response['data']);
    
      assertStrict($response['info']['http_code'], 403);
      assertStrict($json->data->error, $messages["not_enough_permission"]);
    });
  
    it("should return not authorized when trying to send message without authorization", function () {
      global $testsConfig, $messages, $MaxAndIlyaChat;
    
      $response = request("POST", $testsConfig["host"] . "/api/chats/" . $MaxAndIlyaChat['id'] . "/messages");
      $json = json_decode($response['data']);
    
      assertStrict($response['info']['http_code'], 401);
      assertStrict($json->data->error, $messages["not_authenticated"]);
    });
  
    it("should return chat not found for deleted chat", function () {
      global $testsConfig, $MaxDmitriev, $messages, $DeletedChatWithMaxAndMatvey;
    
      $jwt = signJwtForUser($MaxDmitriev);
    
      $response = request("POST", $testsConfig["host"] . "/api/chats/" . $DeletedChatWithMaxAndMatvey["id"] . "/messages", ["headers" => ["Authorization: Bearer $jwt"]]);
      $json = json_decode($response['data']);
    
      assertStrict($response['info']['http_code'], 404);
      assertStrict($json->data->error, $messages["chat_not_found"]);
    });
  
    it("should return chat not found for random chat id", function () {
      global $testsConfig, $MaxDmitriev, $messages;
    
      $jwt = signJwtForUser($MaxDmitriev);
    
      $response = request("POST", $testsConfig["host"] . "/api/chats/randomid/messages", ["headers" => ["Authorization: Bearer $jwt"]]);
      $json = json_decode($response['data']);
    
      assertStrict($response['info']['http_code'], 404);
      assertStrict($json->data->error, $messages["chat_not_found"]);
    });
  });

?>
