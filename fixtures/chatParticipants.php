<?php
  
  @include_once __DIR__ . "/chats.php";
  @include_once  __DIR__ . "/users.php";
  
  global $MaxAndIlyaChat, $MaxDmitriev, $IlyaMehof;
  
  $MaxInChatWithIlya = [
    "chatFk" => $MaxAndIlyaChat["id"],
    "userFk" => $MaxDmitriev["id"],
    "permission" => 2
  ];
  
  $IlyaInChatWithMax = [
    "chatFk" => $MaxAndIlyaChat["id"],
    "userFk" => $IlyaMehof["id"],
    "permission" => 2
  ];
  
  $chatParticipantsFixtures = [$MaxInChatWithIlya, $IlyaInChatWithMax];