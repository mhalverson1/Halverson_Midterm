<?php
require "config.php";
?>
<html>
  <head>
    <title>PHP Test</title>
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <div id="container">
      <h1>GuestBook</h1>
      <?php
        $m = filter_input(INPUT_GET,"m");
        if ($m==1) {
          echo '<p class="success">Your response has been recorded.</p>';
        }
      ?>
      <form action="process.php" method="post">
        <label for="name">Name</label>
        <input name="name" id="name" />
        <br />
        <br />
        <label for="comment">Comment</label>
        <textarea name="comment" id="comment"></textarea>
        <br />
        <br />
        <input type="submit" value="Submit" />
      </form>
      <h1>View our guestbook below:</h1>
      <?php
         $fp = @fopen($config['db'],"r"); // the @ symbol suppresses any error messages
         if ($fp && file_get_contents($config['db'])!="") {
            while (($data = fgetcsv($fp)) !== FALSE) {
              echo "<li>{$data[0]}: {$data[1]}</li>\n";
            }
            fclose($fp);
         } else {
           echo "There are no current entries. Be the first!";
         }
      ?>
    </div>
  </body>
</html>