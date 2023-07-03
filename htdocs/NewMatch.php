<?php
  require('components/util.php');

  function PlayerSelect()
  {
    $players = [];
  ?>
  <div class="player_select" id="player_select">
    <h1>Escolhe os Corno</h1>
    <?php
    $connection = getConnection();
    $result = $connection->query("SELECT PLAYERID, NAME FROM PLAYERS");
    while ($row = $result->fetch_assoc()) {
      PlayerThumbnail($row);
      array_push($players, $row['PLAYERID']);
    }
    $connection->close();
    ?>
    <button id="go_btn" class="clickable menu_button" disabled onclick="console.log('hey')">
      GO!
    </button>
  </div>
  <?php
  while ($playerid = array_shift($players)) {
    PlayerDecklist($playerid);
  }
  ?>
  <?php
  }

  function PlayerThumbnail($playerrow)
  {
    $playerid = $playerrow['PLAYERID'];
    $playername = $playerrow['NAME'];

  ?>
    <div class="clickable thumbnail" id="player_<?= $playerid ?>" onclick="showDecklist(<?= $playerid ?>)">
      <img title='<?= $playername ?>' src='getImage.php?playerid=<?= $playerid ?>' onerror='this.onerror = null; this.src = "images/obsolete.jpg"' />
      <p><?= $playername ?></p>
    </div>
  <?php
  }

  function PlayerDecklist($playerid)
  {
  ?>
    <div id="decklist_<?= $playerid ?>" class="decklist" hidden>
      <h1>Escolhe o Deck</h1>
      <button class="clickable close" onclick="hideDecklist(<?= $playerid ?>)">❌</button>
      <?php
      $connection = getConnection();
      $result = $connection->query(
        "SELECT DECKID, COMMANDER FROM DECKS WHERE PLAYERID = $playerid"
      );
      while ($row = $result->fetch_assoc()) {
        DeckThumbnail($row);
      }
      ?>
    </div>
  <?php
  }

  function DeckThumbnail($deckrow)
  {
    $deckid = $deckrow['DECKID'];
    $commander = $deckrow['COMMANDER'];

  ?>
    <div class="clickable thumbnail" onclick="selectDeck(this, <?= $deckid ?>)">
      <img title='<?= $commander ?>' src='https://api.scryfall.com/cards/named?exact=<?= urlencode($commander) ?>&format=image&version=art_crop' onerror='this.onerror = null; this.src = "images/obsolete.jpg"' />
      <p><?= $commander ?></p>
    </div>
  <?php
  }

  BaseDoc('PlayerSelect');
?>