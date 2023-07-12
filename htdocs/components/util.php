<?
  if ($_SERVER['SCRIPT_NAME'] == $_SERVER["DOCUMENT_ROOT"].'/components/util.php')
    header("Location: /");

  function PageNotFound() {
    http_response_code(404);
    echo "404 - ur mom";
    exit;
  }

  function BaseDoc($CurrentPage) {
    ?>
      <html lang="en">

      <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Babylon Stats</title>
        <link rel="stylesheet" href="/css/globals.css">
        <link rel="stylesheet" href="/css/style.css">
        <script src="/src/main.js" defer></script>
      </head>

      <body>
        <div class="main">
          <div class="center">
            <button class="clickable home" onclick="document.location = '/'"></button>
            <? $CurrentPage() ?>
          </div>
        </div>
      </body>

      </html>
    <?
  }

  function getConnection() {
    $mysqli = mysqli_connect("localhost", "root", "", "mtgstats");
    $mysqli->set_charset('utf8mb4');
    return $mysqli;
  }

  function MenuButton($title, $action) {
    ?>
      <button class='clickable menu_button' onclick="<?=$action?>">
        <?=$title?>
      </button>
    <?
  }

  function PlayerThumbnail($player, $action) {
    $playerid = $player['PLAYERID'];
    $playername = $player['NAME'];

    ?>
      <div class="clickable thumbnail" id="player_<?= $playerid ?>" onclick="<?=$action?>">
        <img title='<?= $playername ?>' src='/getImage.php?playerid=<?= $playerid ?>' onerror='this.onerror = null; this.src = "/images/obsolete.jpg"' />
        <p><?= $playername ?></p>
      </div>
    <?
  }

  function PlayerDecklist($playerid) {
    ?>
      <div id="decklist_<?= $playerid ?>" class="decklist hidden">
        <h1>Escolhe o Deck</h1>
        <button class="clickable close" onclick="hideDecklist(<?= $playerid ?>)">❌</button>
        <?
        $connection = getConnection();
        $result = $connection->query(
          "SELECT DECKID, COMMANDER FROM DECKS WHERE PLAYERID = $playerid"
        );
        while ($row = $result->fetch_assoc()) {
          DeckThumbnail($row);
        }
        ?>
      </div>
    <?
  }

  function DeckThumbnail($deckrow) {
    $deckid = $deckrow['DECKID'];
    $commander = $deckrow['COMMANDER'];

    ?>
      <div class="clickable thumbnail" onclick="selectDeck(this, <?= $deckid ?>)">
        <img title='<?= $commander ?>' src='https://api.scryfall.com/cards/named?exact=<?= urlencode($commander) ?>&format=image&version=art_crop' onerror='this.onerror = null; this.src = "images/obsolete.jpg"' />
        <p><?= $commander ?></p>
      </div>
    <?
  }
?>