<? 
  require($_SERVER["DOCUMENT_ROOT"].'/components/util.php');

  function MainMenu() {
    ?>
    <h1>Cornos da Babilônia</h1>
    <div class="main_menu"><?
      MenuButton("Nova Partida", "document.location.pathname = '/play/'");
      MenuButton("Estatísticas", "document.location.pathname = '/stats/'");
      MenuButton("Jogadores", "document.location.pathname = '/players/'");
      MenuButton("Selecionador de Mesa", "document.location.pathname = '/TableChooser.php'");
    ?></div><?
  }

  BaseDoc('MainMenu');
?>