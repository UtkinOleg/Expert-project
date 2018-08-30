<?php
  include "config.php";
  include "func.php";
  $pid = $_GET["id"];
  $title=$titlepage="Проект №".$pid;
  include "topadmin5.php";
  echo viewp($pid);
  echo "</td></tr></table></td></tr></table></body></html>";
?>