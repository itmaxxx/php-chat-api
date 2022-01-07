<h1>Auth e2e</h1>
<pre>
<?php

@include_once __DIR__ . "/config.php";
@include_once __DIR__ . "/lib/index.php";
@include_once __DIR__ . "/../fixtures/users.php";

describe("[POST] /api/auth/sign-up", function() {
  it("should sign up user and return jwt", function() {
    global $testsConfig;
    
    $body = [
      "username" => "username",
      "password" => "password"
    ];

    $response = request("POST", $testsConfig["host"] . "/api/auth/sign-up", $body);
    // TODO: Catch json decode error here or move this logic to function in tests lib
    //       Because in case we failed to decode json we should show response text
    $json = json_decode($response['data']);

    assertStrict(strlen($json->data->jwt) > 0, true);
  });
});

?>
</pre>
