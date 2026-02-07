<?php

function ConnectDatabaseIAM(): PDO
{

// db.php - Database configuratie
  $host     = 'iam-example-db-server';
  $dbname   = 'IAM';
  $username = 'student';
  $password = 'test1234';

  try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
    die("Database verbinding mislukt: " . $e->getMessage());
  }

  return $pdo;
}

function ConnectDatabaseHRM(): PDO
{

// db.php - Database configuratie
  $host     = 'iam-example-hrm-server';
  $dbname   = 'HRM';
  $username = 'admin';
  $password = 'Test1234!';

  try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
    die("Database verbinding mislukt: " . $e->getMessage());
  }

  return $pdo;
}

function getRoleById(int $id): string {
  $pdo = ConnectDatabaseIAM();

  $sql = "SELECT * FROM roles WHERE idRole = :id";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();

  $record = $stmt->fetch(PDO::FETCH_ASSOC);
  if (is_array($record)) {
    return $record['title'];
  }
  else {
    return '?';
  }
}

function getPermissionById(int $id): string
{
  $pdo = ConnectDatabaseIAM();

  $sql  = "SELECT * FROM permissions WHERE idPermission = :id";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();

  $record = $stmt->fetch(PDO::FETCH_ASSOC);
  if (is_array($record)) {
    return $record['title'];
  } else {
    return '?';
  }
}