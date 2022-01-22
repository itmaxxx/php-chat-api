<h1>Users e2e</h1>
<pre>
<?php
  
  @include_once __DIR__ . "/config.php";
  @include_once __DIR__ . "/lib/index.php";
  @include_once __DIR__ . "/../fixtures/users.php";
  @include_once __DIR__ . "/../locale/en/messages.php";
  @include_once __DIR__ . "/../vendor/autoload.php";
  @include_once __DIR__ . "/../utils/jwt.php";
  
  describe("[GET] /api/users/:userId", function () {
    it("should get user by id", function () {
      global $testsConfig, $MaxDmitriev;
      
      $response = request("GET", $testsConfig["host"] . "/api/users/" . $MaxDmitriev["id"]);
      
      $json = json_decode($response['data']);
      $userData = $json->data->user;
      
      assertStrict($response['info']['http_code'], 200);
      assertStrict($userData->id, $MaxDmitriev["id"]);
      assertStrict($userData->username, $MaxDmitriev["username"]);
      assertStrict(isset($userData->password), false);
    });
    
    it("should return user not found", function () {
      global $testsConfig, $messages;
      
      $response = request("GET", $testsConfig["host"] . "/api/users/random_id");
      $json = json_decode($response['data']);
      
      assertStrict($response['info']['http_code'], 404);
      assertStrict($json->data->error, $messages["user_not_found"]);
    });
  });
  
  describe("[GET] /api/users/me", function () {
    it("should get current user info", function () {
      global $testsConfig, $MaxDmitriev;
      
      $jwt = signJwtForUser($MaxDmitriev);
      
      $response = request("GET", $testsConfig["host"] . "/api/users/me", ["headers" => ["Authorization: Bearer $jwt"]]);
      $json = json_decode($response['data']);
      $userData = $json->data->user;
      
      assertStrict($response['info']['http_code'], 200);
      assertStrict($userData->id, $MaxDmitriev["id"]);
      assertStrict($userData->username, $MaxDmitriev["username"]);
      assertStrict(isset($userData->password), false);
    });
    
    it("should return not authenticated when not valid jwt passed", function () {
      global $testsConfig, $messages;
      
      $response = request("GET", $testsConfig["host"] . "/api/users/me", ["headers" => ["Authorization: Bearer not_valid_jwt"]]);
      $json = json_decode($response['data']);
      
      assertStrict($response['info']['http_code'], 401);
      assertStrict($json->data->error, $messages["not_authenticated"]);
    });
    
    it("should return not authenticated when no authorization header passed", function () {
      global $testsConfig, $messages;
      
      $response = request("GET", $testsConfig["host"] . "/api/users/me");
      $json = json_decode($response['data']);
      
      assertStrict($response['info']['http_code'], 401);
      assertStrict($json->data->error, $messages["not_authenticated"]);
    });
  });
  
  describe("[GET] /api/users/me/chats", function () {
    it("should get current user chats", function () {
      global $testsConfig, $MaxDmitriev;
  
      $jwt = signJwtForUser($MaxDmitriev);
  
      $response = request("GET", $testsConfig["host"] . "/api/users/me/chats", ["headers" => ["Authorization: Bearer $jwt"]]);
      $json = json_decode($response['data']);
      $chats = $json->data->chats;
      
      assertStrict($response['info']['http_code'], 200);
      assertStrict(count($chats), 2);
    });
  });

?>
</pre>
