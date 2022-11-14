<?php 
  require "functions.php";

  // Retreive the input data from the post
  $name = filter_input(INPUT_POST, "name");
  $comment = filter_input(INPUT_POST, "comment");
  addGuestbookEntry($name, $comment);

  // Redirect to the index page with a param to display the "your response has been recorded" message
  header('Location: index.php?m=1');
  exit;

