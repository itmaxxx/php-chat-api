<h1>Users e2e</h1>
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
    it("should get private chat by id for chat member", function () {
      global $testsConfig, $MaxDmitriev, $MaxAndIlyaChat;
      
      $jwt = signJwtForUser($MaxDmitriev);
      
      $response = request("GET", $testsConfig["host"] . "/api/chats/" . $MaxAndIlyaChat["id"], ["headers" => ["Authorization: Bearer $jwt"]]);
      
      $json = json_decode($response['data']);
      $chatData = $json->data->chat;
      
      assertStrict($response['info']['http_code'], 200);
      assertStrict(intval($chatData->id), $MaxAndIlyaChat['id']);
      assertStrict($chatData->name, $MaxAndIlyaChat['name']);
      assertStrict(isset($chatData->isPrivate), false);
      assertStrict(isset($chatData->inviteLink), false);
    });
  
    it("should return error when trying to get private chat by id for NOT a chat member", function () {
      global $testsConfig, $messages, $MatveyGorelik, $MaxAndIlyaChat;
    
      $jwt = signJwtForUser($MatveyGorelik);
    
      $response = request("GET", $testsConfig["host"] . "/api/chats/" . $MaxAndIlyaChat["id"], ["headers" => ["Authorization: Bearer $jwt"]]);
    
      $json = json_decode($response['data']);
    
      assertStrict($response['info']['http_code'], 401);
      assertStrict($json->data->error, $messages["not_authenticated"]);
      assertStrict(isset($chatData->isPrivate), false);
      assertStrict(isset($chatData->inviteLink), false);
    });
  
    it("should get public chat by id", function () {
      global $testsConfig, $MaxDmitriev, $GymPartyPublicChat;
      
      $jwt = signJwtForUser($MaxDmitriev);
    
      $response = request("GET", $testsConfig["host"] . "/api/chats/" . $GymPartyPublicChat["id"], ["headers" => ["Authorization: Bearer $jwt"]]);
    
      $json = json_decode($response['data']);
      $chatData = $json->data->chat;
    
      assertStrict($response['info']['http_code'], 200);
      assertStrict(intval($chatData->id), $GymPartyPublicChat['id']);
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
    
    it("should return chat not found", function () {
      global $testsConfig, $MaxDmitriev, $messages;
      
      $jwt = signJwtForUser($MaxDmitriev);
      
      $response = request("GET", $testsConfig["host"] . "/api/chats/random_id", ["headers" => ["Authorization: Bearer $jwt"]]);
      $json = json_decode($response['data']);
      
      assertStrict($response['info']['http_code'], 404);
      assertStrict($json->data->error, $messages["chat_not_found"]);
    });
  });
  
?>
</pre>