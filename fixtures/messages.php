<?php
  
  @include_once __DIR__ . "/chats.php";
  @include_once  __DIR__ . "/users.php";
  
  global $MaxAndIlyaChat, $MaxDmitriev, $IlyaMehof, $DeletedChatWithMaxAndMatvey, $MatveyGorelik, $GymPartyPublicChat;
  
  $MaxMessageInChatWithIlya = [
    "id" => "message000000001",
    "chatId" => $MaxAndIlyaChat["id"],
    "userId" => $MaxDmitriev["id"],
    "content" => "Hi there, it's our first message"
  ];
  
  $IlyaMessageInChatWithMax = [
    "id" => "message000000002",
    "chatId" => $MaxAndIlyaChat["id"],
    "userId" => $IlyaMehof["id"],
    "content" => "Hi Max! Nice to meet you here!"
  ];
  
  $MaxPhotoMessageInChatWithIlya = [
    "id" => "message000000003",
    "chatId" => $MaxAndIlyaChat["id"],
    "userId" => $MaxDmitriev["id"],
    "contentType" => 1,
    "content" => "https://ixbt.online/live/images/original/04/19/16/2022/01/23/14e5fd3c80.jpg"
  ];
  
  $MaxMessageInDeletedChatWithMatvey = [
    "id" => "message000000004",
    "chatId" => $DeletedChatWithMaxAndMatvey["id"],
    "userId" => $MaxDmitriev["id"],
    "content" => "Hi Matvey, how are you?"
  ];
  
  $MatveyMessageInDeletedChatWithMax = [
    "id" => "message000000005",
    "chatId" => $DeletedChatWithMaxAndMatvey["id"],
    "userId" => $MatveyGorelik["id"],
    "content" => "Hi, I'm fine, can you call me now? I'll delete this chat now"
  ];
  
  $MaxMessageInGymChat = [
    "id" => "message000000006",
    "chatId" => $GymPartyPublicChat["id"],
    "userId" => $MaxDmitriev["id"],
    "content" => "Salam aleykum! Todzhiko ikei vatan, duri mi duhom padat nakin"
  ];
  
  $IlyaMessageInGymChat = [
    "id" => "message000000007",
    "chatId" => $GymPartyPublicChat["id"],
    "userId" => $IlyaMehof["id"],
    "content" => "Nas nikogda nikto ne slomaet"
  ];
  
  $messagesFixtures = [
    $MaxMessageInChatWithIlya,
    $IlyaMessageInChatWithMax,
    $MaxPhotoMessageInChatWithIlya,
    $MaxMessageInDeletedChatWithMatvey,
    $MatveyMessageInDeletedChatWithMax,
    $IlyaMessageInGymChat,
    $MaxMessageInGymChat
  ];
