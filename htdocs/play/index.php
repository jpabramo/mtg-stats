<?
  require($_SERVER["DOCUMENT_ROOT"].'/components/util.php');

  function PlayerSelect() {
    $players = [];

    ?>
      <div class="player_select" id="player_select">
        <h1>Escolhe os Corno</h1>
        <div class="player_list">
          <?
          $connection = getConnection();
          $result = $connection->query("SELECT PLAYERID, NAME FROM PLAYERS");
          while ($row = $result->fetch_assoc()) {
            $playerid = $row['PLAYERID'];
            PlayerThumbnail($row, "showDecklist($playerid)");
            array_push($players, $playerid);
          }
          $connection->close();
          ?>
        </div>
        <button id="go_btn" class="clickable menu_button" disabled onclick="startNewMatch()">
          GO!
        </button>
      </div>
    <?

    while ($playerid = array_shift($players)) {
      PlayerDecklist($playerid);
    }
  }

  BaseDoc('PlayerSelect');
?>

<script src="/src/play/main.js"></script>