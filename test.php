<?php
  $DateMessage = date("d/m/Y H:m", strtotime('2021-05-17 10:35:38'));
  echo "<p >Date : $DateMessage </p>";
  $MaDate=date_create('2021-05-17 10:35:38');
   echo date_format($MaDate, 'd/m/Y H:i:s');
?>