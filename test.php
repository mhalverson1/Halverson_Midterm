<?php
  require "config.php";
  $p = filter_input(INPUT_GET,"p");
  $_SESSION['db'] = $p?$config['prod_db']:$config['test_db'];