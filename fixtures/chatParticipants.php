<?php
  
  @include_once __DIR__ . "/chats.php";
  @include_once  __DIR__ . "/users.php";
  
  global $MaxAndIlyaChat, $MaxDmitriev, $IlyaMehof, $DeletedChatWithMaxAndMatvey, $MatveyGorelik;
  
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
  
  $MaxInDeletedChatWithMatvey = [
    "chatId" => $DeletedChatWithMaxAndMatvey["id"],
    "userId" => $MaxDmitriev["id"],
    "permission" => 2
  ];
  
  $MatveyInDeletedChatWithMax = [
    "chatId" => $DeletedChatWithMaxAndMatvey["id"],
    "userId" => $MatveyGorelik["id"],
    "permission" => 2
  ];
  
  $chatParticipantsFixtures = [$MaxInChatWithIlya, $IlyaInChatWithMax, $MaxInDeletedChatWithMatvey, $MatveyInDeletedChatWithMax];