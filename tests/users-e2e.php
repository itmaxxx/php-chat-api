<h1>Users e2e</h1>
<pre>
<?php

include_once "./tests/lib/index.php";

describe("[GET] /api/users", function() {
  it("should get users list", function() {
    global $usersFixtures;

    $response = request("http://localhost/api/users");
    $json = json_decode($response['data']);

    var_dump($json->data->users);
    var_dump($usersFixtures);

    assertStrict($response['info']['http_code'], 200);
    assertObject($json->data->users, $usersFixtures);

    assertStrict(123, 123);
    assertNotStrict("123", 123);
  });
});

?>
</pre>
