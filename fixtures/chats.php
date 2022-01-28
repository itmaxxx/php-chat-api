<?php
  
  $MaxAndIlyaChat = [
    "id" => "chat000000000001",
    "name" => "First private chat",
    "isPrivate" => 1,
    "image" => "https://memepedia.ru/wp-content/uploads/2019/01/hamster-768x432.jpg",
    "inviteLink" => "invitelink000001"
  ];
  
  $GymPartyPublicChat = [
    "id" => "chat000000000002",
    "name" => "Gym chat",
    "isPrivate" => 0,
    "image" => "https://upload.wikimedia.org/wikipedia/en/thumb/3/3f/Gold%27s_Gym_logo.svg/1200px-Gold%27s_Gym_logo.svg.png",
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
  