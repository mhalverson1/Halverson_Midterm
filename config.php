<?php

$config = [];
$config['test_db'] = "test.csv";
$config['prod_db'] = "guestbook.csv";

$t = filter_input(INPUT_GET,"t");
$config['db'] = $t==1?$config['test_db']:$config['prod_db'];