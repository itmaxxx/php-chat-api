<?php
  
  $MaxAndIlyaChat = [
    "id" => "chat000000000001",
    "name" => "First private chat",
    "isPrivate" => 1,
    "inviteLink" => "invitelink000001"
  ];
  
  $GymPartyPublicChat = [
    "id" => "chat000000000002",
    "name" => "Welcome to the club buddy",
    "isPrivate" => 0,
    "inviteLink" => "invitelink000002"
  ];
  
  $DeletedChatWithMaxAndMatvey = [
    "id" => "chat000000000003",
    "name" => "No one should see this chat",
    "isPrivate" => 1,
    "isDeleted" => 1,
    "inviteLink" => "invitelink000003"
  ];
  
  $chatsFixtures = [$MaxAndIlyaChat, $GymPartyPublicChat, $DeletedChatWithMaxAndMatvey];
  