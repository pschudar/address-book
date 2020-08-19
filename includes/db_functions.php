<?php

function databaseConnect() {
  $connection = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
  databaseConfirm($connection);
  return $connection;
}

function databaseConfirm($connection) {
  if($connection->connect_errno) {
    $msg = "Database connection failed: ";
    $msg .= $connection->connect_error;
    $msg .= " (" . $connection->connect_errno . ")";
    exit($msg);
  }
}

function databaseDisconnect($connection) {
  if(isset($connection)) {
    $connection->close();
  }
}
