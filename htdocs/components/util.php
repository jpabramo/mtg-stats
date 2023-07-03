<?php
if ($_SERVER['SCRIPT_NAME'] == '/components/util.php')
  header("Location: /");

function BaseDoc($CurrentPage)
{
?>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Babylon Stats</title>
    <link rel="stylesheet" href="css/globals.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="src/main.js" defer></script>
  </head>

  <body>
    <div class="main">
      <div class="center">
        <button class="clickable home" onclick="document.location = '/'"></button>
        <?php $CurrentPage() ?>
      </div>
    </div>
  </body>

  </html>
<?php
}

function getConnection()
{
  $mysqli = mysqli_connect("localhost", "root", "", "mtgstats");
  $mysqli->set_charset('utf8mb4');
  return $mysqli;
}

?>