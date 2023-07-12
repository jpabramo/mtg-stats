<?
  require($_SERVER["DOCUMENT_ROOT"].'/components/util.php');

  function LoadMatch() {
    $matchid = $_GET["matchid"];
    
    $connection = getConnection();
    $stmt = $connection->prepare("SELECT deckid, seat, matchdetails.order FROM mtgstats.matchdetails WHERE matchid = ?");
    $stmt->execute([$matchid]);
    $result = $stmt->get_result();
    
    if(!$result || $result->num_rows == 0)  {
      $connection->close();
      PageNotFound();
    }

    $GLOBALS['playercount'] = $result->num_rows;

    ?>
      <script>
        var matchdetails = [];
        <?
          while($row = $result->fetch_assoc()) 
            echo "matchdetails.push(" . json_encode($row) . ");";
        ?>
      </script>
    <?

    $stmt = $connection->prepare("SELECT * FROM mtgstats.matchevents WHERE matchid = ?");
    $stmt->execute([$matchid]);
    $result = $stmt->get_result();
    
    if($result && $result->num_rows > 0) {
      ?>
        <script>
          var matchevents = [];
          <?
            while($row = $result->fetch_assoc()) 
              echo "matchevents.push(" . json_encode($row) . ");";
          ?>
        </script>
      <?
    }

    $connection->close();
  }
  
  function StartNewMatch() {
    if(!array_key_exists("startMatch", $_GET)) PageNotFound();
    
    $deckstr = $_GET["startMatch"];
    $decks = explode(",", $deckstr);
    $connection = getConnection();
    $connection->query("INSERT INTO mtgstats.matches() VALUES()");
    $matchid = $connection->insert_id;
    
    foreach($decks as $deckid) {
      $stmt = $connection->prepare("INSERT INTO mtgstats.matchdetails(matchid, deckid) VALUES (?, ?)");
      $stmt->execute([$matchid, $deckid]);
    }

    $connection->commit();
    $connection->close();

    header("Location: /play/play.php?matchid=$matchid");
  }
  
  if(array_key_exists("matchid", $_GET)) LoadMatch();
  else StartNewMatch();

  function PlayArea() {
    ?>
      <div id="playarea" class="playarea">
        <?
          for ($t = 0; $t < $GLOBALS['playercount']; $t++) 
            Player($t);
        ?>
      </div>
    <?
  }

  function Player($seat) {
    ?>
      <div class="playmat" id="seat<?=$seat?>">
        <div class="lifecounter">40</div>
        <button 
          class="lifebtn upper" 
          onmousedown="incrementLife(<?=$seat?>)"
          onclickmove="console.log('draaaag')"  
        ></button>
        <button class="lifebtn lower" onclick="decrementLife(<?=$seat?>)"></button>
      </div>
    <?
  }

  function LayoutPicker() {
    ?>
      <div id="layout_picker" class="layout_picker hidden">
        <div class="center">
          <button onclick="pickLayout(0)">1</button>
          <button onclick="pickLayout(1)">2</button>
        </div>
      </div>
    <?
  }

  function DeckChooser($seat) {
    ?>
      <div class="deck_chooser"></div>
    <?
  }
?>

<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BABYLON</title>
  <link rel="stylesheet" href="/css/globals.css">
  <link rel="stylesheet" href="/css/play.css">
  <script src="/lib/jquery-3.7.0.min.js"></script>
  <script src="/src/play.js" defer></script>
</head>
<body>
  <?=PlayArea()?>
  <?=LayoutPicker()?>
</body>
</html>