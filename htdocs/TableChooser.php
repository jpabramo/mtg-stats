<?
  require($_SERVER["DOCUMENT_ROOT"].'/components/util.php');

  function chooseTable($playerString) {
    if(!preg_match('/^\d+(,\d+)+$/', $playerString)) return false;

    $connection = getConnection();
    $stmt = $connection->prepare(
      "SELECT * 
        FROM PLAYERGAMES 
        WHERE PLAYID1 IN ($playerString) 
        AND PLAYID2 IN ($playerString)"
    );
    $stmt->execute();
    $result = $stmt->get_result();
    
    $games = [];
    $names = [];
    while($row = $result->fetch_assoc()) {
      $games[$row["playid1"]][$row["playid2"]] = $row["games"];
      $names[$row["playid1"]] = $row["name1"];
      $names[$row["playid2"]] = $row["name2"];
    }
    $connection->close();

    $players = explode(',', $playerString);
    $tableSizes = getTables(count($players));

    $possibilities = permutations($players, $tableSizes);
    $averages = [];
    for($t = 0; $t < count($possibilities); $t++) {
      $perm = $possibilities[$t];
      $avg = 0;
      foreach($perm as $g) {
        $avg += gamesAverage($g, $games);
      }

      $averages[$t] = $avg;
    };

    for($t = 0; $t < count($averages); $t++) {
      $min = $averages[$t];
      $min_index = $t;

      for($i = $t+1; $i < count($averages); $i++) {
        if($averages[$i] < $min) {
          $min = $averages[$i];
          $min_index = $i;
        }
      }

      $aux = $averages[$t];
      $averages[$t] = $min;
      $averages[$min_index] = $aux;

      $aux = $possibilities[$t];
      $possibilities[$t] = $possibilities[$min_index];
      $possibilities[$min_index] = $aux;
    }

    $minavg = $averages[0];
    $range = 0;

    while(count($averages) < ++$range && $averages[$range] == $minavg);

    $less_poss = array_slice($possibilities, 0, $range);

    $chosen = $less_poss[rand(0, $range-1)];


    ?>
      <div class="table_menu" id="table_menu">
        <h1 id="tables_title">Mesas</h1>
    <?

    for($t = 0; $t < count($chosen); $t++) {
      $tab = $chosen[$t];

      $players = [];
      foreach($tab as $playerid) $players[] = [
        'PLAYERID' => $playerid, 
        'NAME' => $names[$playerid]
      ];

      PlayerTable($players, $t);
    }

    ?>
      </div>
    <?

    foreach($chosen as $tab) 
      foreach($tab as $playerid) 
        PlayerDecklist($playerid);

    return true;
  }

  function PlayerTable($players, $tableid) {
    ?>
      <div class="table" id="table_<?=$tableid?>">
        <div class="table_num">
          <span>Mesa <?=$tableid+1?></span>
        </div>
        <div class="table_players">
    <?
      foreach($players as $player) {
        $playerid = $player['PLAYERID'];
        PlayerThumbnail(
          $player,
          "showDecklist($playerid)"
        );
      }  
    ?>
      </div>
      <button 
        id="go_btn_<?=$tableid?>" 
        class="clickable table_button"
        disabled
        onclick="startMatch(<?=$tableid?>)">
        GO!
      </button>
      </div>
      <script>
        if(window.matches == undefined) window.matches = [];
        matches[<?=$tableid?>] = [];

        if(window.match_sizes == undefined) window.match_sizes = [];
        match_sizes[<?=$tableid?>] = <?=count($players)?>;
      </script>
    <?
  }

  function getTables($num) {
    $p = 0;
    $g = 0;
    
    while($p < floor($num/3)) {
      $g = ($num - 3*$p)/4;
      if(floor($g) == $g) break;
      $p++;
    }

    $retval = [];

    for($t = 0; $t < floor($g); $t++) {
      if($num < 4) break;
      $retval[] = 4;
      $num -= 4;
    }

    for($t = 0; $t < floor($p); $t++) {
      if($num < 3) break;
      $retval[] = 3;
      $num -= 3;
    }

    if($num) $retval[] = $num;

    return $retval;
  }

  function gamesAverage($playerlist, $games) {
    $length = count($playerlist);

    if($length < 2) return 0;
    if($length == 2) {
      if(
        array_key_exists($playerlist[0], $games) &&
        array_key_exists($playerlist[1], $games[$playerlist[0]])) 
        return $games[$playerlist[0]][$playerlist[1]];
      else return $games[$playerlist[1]][$playerlist[0]];
    }
    
    $retval = 0;
    $numgames = 0;

    for($t = 0; $t < $length; $t++) {
      $reduxlist = $playerlist;
      array_splice($reduxlist, $t, 1);
      $retval = ($retval*$numgames + gamesAverage($reduxlist, $games))/(++$numgames);
    }

    return $retval;
  }

  function subsets($array, $length) {
    if(count($array) <= $length) return [$array];

    $retval = [];
    
    for($t = 0; $t < count($array); $t++) {
      $aux = $array;
      array_splice($aux, $t, 1);
      foreach(subsets($aux, $length) as $sub) $retval[] = $sub;
    }

    return array_unique($retval, SORT_REGULAR);
  }

  function permuteTables($players, $tables) {
    
    if($tables[4] > 0) {
      $retval = [];
      $sub = subsets($players, 4);
      foreach($sub as $set) {
        $aux = array_diff($players, $set);
        $tables[4]--;
        $permutations = permuteTables($aux, $tables);
        if(count($permutations) == 0) $retval[] = $set;
        else foreach($permutations as $perm) $retval[] = [$set, $perm];
      }
      
      return $retval;
    }
    
    if($tables[3] > 0) {
      $retval = [];
      $sub = subsets($players, 3);
      foreach($sub as $set) {
        $aux = array_diff($players, $set);
        $tables[3]--;
        foreach(permuteTables($aux, $tables) as $perm) $retval[] = [$set, $perm];
      }
      
      return $retval;
    }

    return [];
  }

  function permutations($array, $groups) {
    if(count($array) == 0) return [[]];
    if(count($groups) == 0) return [[$array]];
    
    $retval = [];
    $sub = subsets($array, array_shift($groups));
    
    foreach($sub as $set) {
      $aux = array_diff($array, $set);
      foreach(permutations($aux, $groups) as $perm) {        
        array_unshift($perm, $set);
        $retval[] = $perm;
      }
    }

    return $retval;
  }
  
  function playerChooser() {
    ?><h1>Quem tá jogando?</h1><?

    $connection = getConnection();
    $result = $connection->query("SELECT PLAYERID, NAME FROM PLAYERS");
    ?><div class="player_list" id="player_list"><?
      while ($row = $result->fetch_assoc()) {
        $playerid = $row['PLAYERID'];
        PlayerThumbnail($row, "choosePlayer($playerid)");
        // array_push($players, $playerid);
      }
    ?>
      </div>
      <button id="go_btn" class="clickable menu_button" disabled onclick="chooseTables()">
        GO!
      </button>
    <?
    $connection->close();
    
  }
  
  function TableChooser() {
    if(array_key_exists('players', $_GET) && chooseTable($_GET["players"])) return;
    playerChooser();
  }

  BaseDoc('TableChooser');

?>

<script src="/src/tableChooser.js"></script>