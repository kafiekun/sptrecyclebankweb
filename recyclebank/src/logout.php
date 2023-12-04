<?php
session_start();
require 'connect.php';

if (isset($_GET['logout'])) {
  $rfid = $_SESSION['rfid'];

  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sqlpoint = "UPDATE trans_Virtue_BANK SET kanal = kanal + 5 WHERE id_ST = ?";
  $q = $pdo->prepare($sqlpoint);
  $q->execute([$rfid]);
  Database::disconnect();
}

if(isset($_SESSION['rfid']))
    session_destroy();
    unset($_SESSION['rfid']);
    header('location: login');

?>