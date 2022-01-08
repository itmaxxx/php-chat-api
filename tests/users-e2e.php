<h1>Users e2e</h1>
<pre>
<?php
  
  @include_once __DIR__ . "/config.php";
  @include_once __DIR__ . "/lib/index.php";
  @include_once __DIR__ . "/../fixtures/users.php";
  
  describe("[GET] /api/users", function () {
    it("should get users list", function () {
      global $testsConfig;
      global $usersFixtures;
      
      $response = request("GET", $testsConfig["host"] . "/api/users");
      $json = json_decode($response['data']);
      
      assertStrict($response['info']['http_code'], 200);
      assertStrict(count($json->data->users), count($usersFixtures));
    });
  });
  
  describe("[GET] /api/users/:userId", function () {
    it("should get user by id", function () {
      global $testsConfig;
      global $MaxDmitriev;
      
      $response = request("GET", $testsConfig["host"] . "/api/users/" . $MaxDmitriev["id"]);
      
      $json = json_decode($response['data']);
      $userData = $json->data->user;
      
      assertStrict($response['info']['http_code'], 200);
      assertStrict(intval($userData->id), $MaxDmitriev["id"]);
      assertStrict($userData->username, $MaxDmitriev["username"]);
      assertStrict(isset($userData->password), false);
    });
  })

?>
</pre>
