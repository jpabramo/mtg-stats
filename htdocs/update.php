<?
  exec("pwsh ".$_SERVER["DOCUMENT_ROOT"]."/../bin/update.ps1");
  header('Location: /');
?>