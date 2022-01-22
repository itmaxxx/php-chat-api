<?php
  
  function randomId($length = 8): string
  {
    $bytes = random_bytes($length);
    return bin2hex($bytes);
  }