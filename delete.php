<?php
// Conexiune baza de date
require_once 'db.php';

// Verificăm dacă avem un ID transmis prin URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Căutăm produsul ca să găsim numele imaginii asociate
    $stmt = $pdo->prepare("SELECT image FROM products WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $product = $stmt->fetch();

    // Dacă produsul are o poză salvată, o ștergem fizic de pe server
    if ($product && !empty($product['image'])) {
        $imagePath = 'uploads/' . $product['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath); // Șterge fișierul de pe disc
        }
    }

    // Ștergem rândul din baza de date folosind un Prepared Statement
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

// Redirecționăm utilizatorul înapoi la pagina principală
header('Location: index.php');
exit();
?>