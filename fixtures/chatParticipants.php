<?php
  
  @include_once __DIR__ . "/chats.php";
  @include_once  __DIR__ . "/users.php";
  
  global $MaxAndIlyaChat, $MaxDmitriev, $IlyaMehof;
  
  $MaxInChatWithIlya = [
    "chatId" => $MaxAndIlyaChat["id"],
    "userId" => $MaxDmitriev["id"],
    "permission" => 2
  ];
  
  $IlyaInChatWithMax = [
    "chatId" => $MaxAndIlyaChat["id"],
    "userId" => $IlyaMehof["id"],
    "permission" => 2
  ];
  
  $chatParticipantsFixtures = [$MaxInChatWithIlya, $IlyaInChatWithMax];