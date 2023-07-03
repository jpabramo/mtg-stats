<?php
  exec("pwsh ../bin/update.ps1");
  header('Location: /');
?>