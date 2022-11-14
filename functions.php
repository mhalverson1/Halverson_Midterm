<?php
  require_once "config.php";

function deleteGuestbook() {
  global $config;
  unlink($config['db']);
  file_put_contents($config['db'],"");
}

function addGuestbookEntry($name, $comment) {
  global $config;
  // Store the data into a CSV file
  $fp = fopen($config['db'],"a");
  fputcsv($fp, [$name, $comment]);
  fclose($fp);
}
