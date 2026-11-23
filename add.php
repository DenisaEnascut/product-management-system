<?php
// Conexiune baza de date
require_once 'db.php';

$message = "";

// Când se trimite formularul (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Preluăm datele și curățăm spațiile
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $availability_date = $_POST['availability_date'] ?? null;
    $stock_status = isset($_POST['stock_status']) ? 1 : 0;
    
    // Verificăm câmpurile obligatorii
    if (!empty($name) && !empty($price)) {
        
        $imageName = "";
        // Gestionarea upload-ului de imagine
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            // Nume unic cu time() ca să nu se suprascrie fișierele
            $imageName = time() . '_' . basename($fileName);
            $uploadFileDir = 'uploads/';
            
            // Cream folderul uploads automat dacă nu există
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0777, true);
            }
            
            // Mutăm fișierul din folderul temporar în destinația finală
            move_uploaded_file($fileTmpPath, $uploadFileDir . $imageName);
        }

        try {
            // Inserăm produsul în baza de date cu Prepared Statements
            $sql = "INSERT INTO products (name, description, price, image, availability_date, stock_status) 
                    VALUES (:name, :description, :price, :image, :availability_date, :stock_status)";
            
            $stmt = $pdo->prepare($sql);
            
            $stmt->execute([
                ':name' => $name,
                ':description' => $description,
                ':price' => $price,
                ':image' => $imageName,
                ':availability_date' => $availability_date,
                ':stock_status' => $stock_status
            ]);

            // Redirecționăm la listă după succes
            header("Location: index.php");
            exit();

        } catch (PDOException $e) {
            $message = "Eroare la adăugarea produsului: " . $e->getMessage();
        }

    } else {
        $message = "Te rog să completezi cel puțin numele și prețul produsului!";
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Adaugă Produs Nou</title>
    <!-- Folosim fișierul extern style.css -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>Product Management System</h1>
    <h2>Adaugă un Produs Nou</h2>

    <!-- Afișăm eroarea dacă există -->
    <?php if (!empty($message)): ?>
        <p class="error"><?= $message ?></p>
    <?php endif; ?>

    <!-- Formular pentru adăugare (necesită enctype pentru fișiere) -->
    <form action="add.php" method="POST" enctype="multipart/form-data">
        
        <div class="form-group">
            <label for="name">Name *</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description"></textarea>
        </div>

        <div class="form-group">
            <label for="price">Price (RON) *</label>
            <input type="number" step="0.01" id="price" name="price" required>
        </div>

        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" id="image" name="image">
        </div>

        <div class="form-group">
            <label for="availability_date">Availability Date</label>
            <input type="date" id="availability_date" name="availability_date">
        </div>

        <div class="form-group">
            <label>
                <!-- Checkbox pentru stoc (bifat default) -->
                <input type="checkbox" name="stock_status" value="1" checked> În stoc
            </label>
        </div>

        <button type="submit">Salvează Produsul</button>
    </form>

    <br>
    <a href="index.php" class="back-link">&larr; Înapoi la Lista de Produse</a>

</body>
</html>