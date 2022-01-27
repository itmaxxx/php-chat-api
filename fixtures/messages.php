<?php
  
  @include_once __DIR__ . "/chats.php";
  @include_once  __DIR__ . "/users.php";
  
  global $MaxAndIlyaChat, $MaxDmitriev, $IlyaMehof, $DeletedChatWithMaxAndMatvey, $MatveyGorelik, $GymPartyPublicChat;
  
  $MaxMessageInChatWithIlya = [
    "id" => "message000000001",
    "chatId" => $MaxAndIlyaChat["id"],
    "userId" => $MaxDmitriev["id"],
    "content" => "Hi there, it's our first message",
    "createdAt" => time() - 20
  ];
  
  $IlyaMessageInChatWithMax = [
    "id" => "message000000002",
    "chatId" => $MaxAndIlyaChat["id"],
    "userId" => $IlyaMehof["id"],
    "content" => "Hi Max! Nice to meet you here!",
    "createdAt" => time() - 19
  ];
  
  $MaxPhotoMessageInChatWithIlya = [
    "id" => "message000000003",
    "chatId" => $MaxAndIlyaChat["id"],
    "userId" => $MaxDmitriev["id"],
    "contentType" => 1,
    "content" => "https://ixbt.online/live/images/original/04/19/16/2022/01/23/14e5fd3c80.jpg",
    "createdAt" => time() - 18
  ];
  
  $MaxMessageInDeletedChatWithMatvey = [
    "id" => "message000000004",
    "chatId" => $DeletedChatWithMaxAndMatvey["id"],
    "userId" => $MaxDmitriev["id"],
    "content" => "Hi Matvey, how are you?",
    "createdAt" => time() - 17
  ];
  
  $MatveyMessageInDeletedChatWithMax = [
    "id" => "message000000005",
    "chatId" => $DeletedChatWithMaxAndMatvey["id"],
    "userId" => $MatveyGorelik["id"],
    "content" => "Hi, I'm fine, can you call me now? I'll delete this chat now",
    "createdAt" => time() - 16
  ];
  
  $MaxMessageInGymChat = [
    "id" => "message000000006",
    "chatId" => $GymPartyPublicChat["id"],
    "userId" => $MaxDmitriev["id"],
    "content" => "Salam aleykum! Todzhiko ikei vatan, duri mi duhom padat nakin",
    "createdAt" => time() - 15
  ];
  
  $IlyaMessageInGymChat = [
    "id" => "message000000007",
    "chatId" => $GymPartyPublicChat["id"],
    "userId" => $IlyaMehof["id"],
    "content" => "Nas nikogda nikto ne slomaet",
    "createdAt" => time() - 14
  ];
  
  $MaxSecondMessageInChatWithIlya = [
    "id" => "message000000008",
    "chatId" => $MaxAndIlyaChat["id"],
    "userId" => $MaxDmitriev["id"],
    "contentType" => 0,
    "content" => "Have you seen first tesla phone renders?",
    "createdAt" => time() - 13
  ];
  
  $messagesFixtures = [
    $MaxMessageInChatWithIlya,
    $IlyaMessageInChatWithMax,
    $MaxPhotoMessageInChatWithIlya,
    $MaxMessageInDeletedChatWithMatvey,
    $MatveyMessageInDeletedChatWithMax,
    $IlyaMessageInGymChat,
    $MaxMessageInGymChat,
    $MaxSecondMessageInChatWithIlya
  ];
