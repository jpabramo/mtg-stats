<?
  require($_SERVER["DOCUMENT_ROOT"].'/components/util.php');

  $reqBody = file_get_contents("php://input");
  $matchdata = json_decode($reqBody);
  $matchdetails = $matchdata['details'];
  $matchevents = $matchdata['events'];
?>