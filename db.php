<?php
$host='localhost';
$db='product_management';
$user='root';
$pass='';

/*
$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);

if (!$pdo) {
    die("Conexiunea a eșuat!");
}
*/

try{
    $pdo=new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);

    // Setăm modul de eroare să arunce excepții
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Setăm modul implicit de preluare a datelor ca array asociativ
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
        // Dacă conexiunea eșuează, oprim totul și afișăm eroarea
        die("Eroare de conexiune: " . $e->getMessage());
    }

?>