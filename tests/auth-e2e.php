<h1>Auth e2e</h1>
<pre>
<?php
  
  @include_once __DIR__ . "/config.php";
  @include_once __DIR__ . "/lib/index.php";
  @include_once __DIR__ . "/../fixtures/users.php";
  @include_once __DIR__ . "/../locale/en/messages.php";
  
  describe("[POST] /api/auth/sign-up", function () {
    it("should sign up user and return jwt", function () {
      global $testsConfig;
      
      $body = [
        "username" => "username",
        "password" => "password"
      ];
      
      $response = request("POST", $testsConfig["host"] . "/api/auth/sign-up", ["json" => $body]);
      // TODO: Catch json decode error here or move this logic to function in tests lib
      //       Because in case we failed to decode json we should show response text
      $json = json_decode($response['data']);
      
      assertStrict($response['info']['http_code'], 200);
      assertStrict(strlen($json->data->jwt) > 0, true);
    });
    
    it("should return error when trying to sign up with existing username", function () {
      global $testsConfig, $MaxDmitriev, $messages;
      
      $body = [
        "username" => $MaxDmitriev["username"],
        "password" => "password"
      ];
      
      $response = request("POST", $testsConfig["host"] . "/api/auth/sign-up", ["json" => $body]);
      $json = json_decode($response['data']);
      
      assertStrict($response['info']['http_code'], 400);
      assertStrict($json->data->error, $messages["username_taken"]);
    });
  });
  
  describe("[POST] /api/auth/sign-in", function () {
    it("should sign in user and return jwt", function () {
      global $testsConfig, $MaxDmitriev;
      
      $body = [
        "username" => $MaxDmitriev["username"],
        "password" => $MaxDmitriev["password"]
      ];
      
      $response = request("POST", $testsConfig["host"] . "/api/auth/sign-in", ["json" => $body]);
      $json = json_decode($response['data']);
      
      assertStrict($response['info']['http_code'], 200);
      assertStrict(strlen($json->data->jwt) > 0, true);
    });
    
    it("should return error when password doesnt match", function () {
      global $testsConfig, $MaxDmitriev, $messages;
      
      $body = [
        "username" => $MaxDmitriev["username"],
        "password" => "random_password"
      ];
      
      $response = request("POST", $testsConfig["host"] . "/api/auth/sign-in", ["json" => $body]);
      $json = json_decode($response['data']);
      
      assertStrict($response['info']['http_code'], 401);
      assertStrict($json->data->error, $messages["failed_to_sign_in"]);
    });
    
    it("should return error when trying to sign in with not existing username", function () {
      global $testsConfig, $messages;
      
      $body = [
        "username" => "random_username",
        "password" => "password"
      ];
      
      $response = request("POST", $testsConfig["host"] . "/api/auth/sign-in", ["json" => $body]);
      $json = json_decode($response['data']);
      
      assertStrict($response['info']['http_code'], 400);
      assertStrict($json->data->error, $messages["user_not_found"]);
    });
  });

?>
</pre>
