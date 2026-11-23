<?php
// Conexiune baza de date
require_once 'db.php';

// Verificăm dacă avem un ID valid în URL
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id = $_GET['id'];

// Luăm datele vechi ale produsului din baza de date
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute(['id' => $id]);
$product = $stmt->fetch();

// Dacă produsul nu există, direcționăm înapoi la listă
if (!$product) {
    header('Location: index.php');
    exit();
}

$error = '';

// Când se trimite formularul de editare (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $availability_date = $_POST['availability_date'];
    $stock_status = isset($_POST['stock_status']) ? 1 : 0;
    
    // Păstrăm imaginea veche implicit (dacă nu se schimbă nimic)
    $imageName = $product['image'];

    // Verificăm dacă s-a încărcat o poză nouă
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileExtension, $allowedExtensions)) {
            // Nume unic cu md5 și timp
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = 'uploads/';
            
            // Cream folderul uploads dacă nu există
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }
            
            $dest_path = $uploadFileDir . $newFileName;
            
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // Ștergem vechea poză de pe server să nu ocupe spațiu degeaba
                if (!empty($product['image']) && file_exists($uploadFileDir . $product['image'])) {
                    unlink($uploadFileDir . $product['image']);
                }
                $imageName = $newFileName;
            }
        }
    }

    // Validare simplă
    if (empty($name) || empty($price)) {
        $error = 'Numele și prețul sunt obligatorii!';
    } else {
        // Facem UPDATE în baza de date
        $sql = "UPDATE products SET name = :name, description = :description, price = :price, image = :image, availability_date = :availability_date, stock_status = :stock_status WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'image' => $imageName,
            'availability_date' => $availability_date,
            'stock_status' => $stock_status,
            'id' => $id
        ]);

        header('Location: index.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Editează Produs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Editează Produsul</h1>
        
        <!-- Afișăm eroarea dacă există -->
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?= $error ?></p>
        <?php endif; ?>

        <!-- Formular precompletat cu datele existente -->
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Nume Produs:</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Descriere:</label>
                <textarea id="description" name="description"><?= htmlspecialchars($product['description']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="price">Preț (RON):</label>
                <input type="number" step="0.01" id="price" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>
            </div>

            <div class="form-group">
                <label>Imagine Curentă:</label><br>
                <?php if (!empty($product['image']) && file_exists('uploads/' . $product['image'])): ?>
                    <img src="uploads/<?= $product['image'] ?>" width="80" style="margin-bottom: 10px;"><br>
                <?php else: ?>
                    <p><i>Fără poză</i></p>
                <?php endif; ?>
                <label for="image">Schimbă Imaginea (opțional):</label>
                <input type="file" id="image" name="image">
            </div>

            <div class="form-group">
                <label for="availability_date">Data Disponibilitate:</label>
                <input type="date" id="availability_date" name="availability_date" value="<?= htmlspecialchars($product['availability_date']) ?>">
            </div>

            <div class="form-group">
                <label>
                    <!-- Bifăm checkbox-ul dacă în baza de date stocul e 1 -->
                    <input type="checkbox" name="stock_status" <?= $product['stock_status'] ? 'checked' : '' ?>> În stoc
                </label>
            </div>

            <button type="submit" class="btn">Salvează Modificările</button>
            <a href="index.php" style="margin-left: 10px;">Anulează</a>
        </form>
    </div>
</body>
</html>