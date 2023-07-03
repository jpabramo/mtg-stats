<?php 
  require('components/util.php');

  function MenuButton($title, $action) {
    ?>
      <button class='clickable menu_button' onclick="<?=$action?>">
        <?=$title?>
      </button>
    <?php
  }

  function MainMenu() {
    ?>
    <h1>Cornos da Babilônia</h1>
    <div class="main_menu"><?php
      MenuButton("Nova Partida", "document.location.pathname = '/NewMatch.php'");
      MenuButton("Estatísticas", "document.location.pathname = '/PlayerStats.php'");
      MenuButton("Gerenciamento", "document.location.pathname = '/Management.php'");
    ?></div><?php
  }

  BaseDoc('MainMenu');
?>