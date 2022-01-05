<h1>Users e2e</h1>
<pre>
<?php

@include_once __DIR__ . "/config.php";
@include_once __DIR__ . "/lib/index.php";
@include_once __DIR__ . "/../fixtures/users.php";

describe("[GET] /api/users", function() {
  it("should get users list", function() {
    global $testsConfig;
    global $usersFixtures;

    $response = request($testsConfig["host"] . "/api/users");
    $json = json_decode($response['data']);

    # var_dump($json->data->users);
    # var_dump($usersFixtures);

    # assertStrict($response['info']['http_code'], 200);
    # assertObject($json->data->users, $usersFixtures);
  });

  it("should get user by id", function() {
    global $testsConfig;
    global $MaxDmitriev;

    $response = request($testsConfig["host"] . "/api/users/" . $MaxDmitriev["id"]);
    $json = json_decode($response['data']);

    assertStrict($response['info']['http_code'], 200);
    assertObject($json->data->user, $MaxDmitriev);
  });
});

?>
</pre>
