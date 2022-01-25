<?php
  
  @include_once __DIR__ . "/chats.php";
  @include_once  __DIR__ . "/users.php";
  @include_once __DIR__ . "/messages.php";
  
  global $MaxAndIlyaChat, $MaxDmitriev, $IlyaMehof, $DeletedChatWithMaxAndMatvey, $MatveyGorelik, $GymPartyPublicChat, $IlyaMessageInChatWithMax;
  
  $MaxInChatWithIlya = [
    "chatId" => $MaxAndIlyaChat["id"],
    "userId" => $MaxDmitriev["id"],
    "permission" => 2
  ];
  
  $IlyaInChatWithMax = [
    "chatId" => $MaxAndIlyaChat["id"],
    "userId" => $IlyaMehof["id"],
    "permission" => 2,
    "lastSeenMessageId" => $IlyaMessageInChatWithMax["id"]
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
  
  $MaxInGymChat = [
    "chatId" => $GymPartyPublicChat["id"],
    "userId" => $MaxDmitriev["id"],
    "permission" => 2
  ];
  
  $IlyaInGymChat = [
    "chatId" => $GymPartyPublicChat["id"],
    "userId" => $IlyaMehof["id"],
    "permission" => 0
  ];
  
  $chatParticipantsFixtures = [$MaxInChatWithIlya, $IlyaInChatWithMax, $MaxInDeletedChatWithMatvey, $MatveyInDeletedChatWithMax, $IlyaInGymChat, $MaxInGymChat];