<?php
// Conexiune baza de date
require_once 'db.php';

// Preluăm ce a căutat userul (dacă a căutat ceva)
$search = trim($_GET['search'] ?? '');

// Limităm la 10 produse pe pagină
$limit = 10; 

// Aflăm pagina curentă (default e 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Calculăm de unde începe afișarea în baza de date
$offset = ($page - 1) * $limit;

// Verificăm dacă avem căutare activă sau afișăm tot
if (!empty($search)) {
    // Numărăm doar produsele care se potrivesc cu căutarea
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE name LIKE :search");
    $countStmt->execute(['search' => '%' . $search . '%']);
    $totalProducts = $countStmt->fetchColumn();

    // Luăm produsele filtrate pentru pagina curentă
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE :search ORDER BY id DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
} else {
    // Numărăm tot ce avem în tabel
    $totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();

    // Luăm produsele pentru pagina curentă
    $stmt = $pdo->prepare("SELECT * FROM products ORDER BY id DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
}

$products = $stmt->fetchAll();

// Calculăm câte pagini totale avem nevoie
$totalPages = ceil($totalProducts / $limit);
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Product Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Product Management System</h1>
    <h2>Lista de produse</h2>
    <a href="add.php" class="btn-add">+ Adaugă Produs Nou</a>
    
    <!-- Formular căutare după nume -->
    <form method="GET" action="index.php" style="margin-bottom: 20px;">
        <input type="text" name="search" placeholder="Caută produs după nume..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" style="padding: 8px; width: 250px;">
        <button type="submit" style="padding: 8px 15px;">Caută</button>
        <?php if (!empty($_GET['search'])): ?>
            <a href="index.php" style="margin-left: 10px; text-decoration: none; color: gray;">Resetează</a>
        <?php endif; ?>
    </form>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Image</th>
                <th>Availability Date</th>
                <th>Stock Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($products) > 0): ?>
                <?php foreach($products as $product): ?>
                    <tr>
                        <!-- Afișăm datele securizat cu htmlspecialchars -->
                        <td><?= htmlspecialchars($product['name']) ?></td> 
                        <td><?= htmlspecialchars($product['description']) ?></td>
                        <td><?= htmlspecialchars($product['price']) ?> RON</td>
                        <td> 
                            <?php if (!empty($product['image'])): ?>
                                <img src="uploads/<?= htmlspecialchars($product['image']) ?>" width="50" alt="Produs">
                            <?php else: ?>
                                Fără poză
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($product['availability_date']) ?></td>
                        <td><?= $product['stock_status'] == 1 ? 'În stoc' : 'Epuizat' ?></td>
                        <td>
                            <!-- Butoane de acțiune pentru fiecare rând -->
                            <a href="edit.php?id=<?= $product['id'] ?>" style="color: blue; text-decoration: none; font-weight: bold; margin-right: 10px;">Editează</a>
                            <a href="#" onclick="openDeleteModal(<?= $product['id'] ?>); return false;" style="color: red; text-decoration: none; font-weight: bold;">Șterge</a>                        
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Mesaj fallback dacă tabelul e gol -->
                <tr>
                    <td colspan="7" style="text-align: center;">Nu există produse în baza de date momentan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Paginare -->
    <?php if ($totalPages > 1): ?>
        <div style="margin-top: 20px; text-align: center;">
            <!-- Buton pagina anterioară -->
            <?php if ($page > 1): ?>
                <a href="index.php?page=<?= $page - 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>" style="padding: 8px 12px; margin-right: 5px; border: 1px solid #ccc; text-decoration: none; background: #f9f9f9; color: #333;">Anterior</a>
            <?php endif; ?>

            <!-- Listare numere pagini -->
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="index.php?page=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>" style="padding: 8px 12px; margin-right: 5px; border: 1px solid #ccc; text-decoration: none; <?= $i === $page ? 'background: #007bff; color: white; font-weight: bold;' : 'background: #f9f9f9; color: #333;' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <!-- Buton pagina următoare -->
            <?php if ($page < $totalPages): ?>
                <a href="index.php?page=<?= $page + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>" style="padding: 8px 12px; border: 1px solid #ccc; text-decoration: none; background: #f9f9f9; color: #333;">Următor</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>            

    <!-- Modal confirmare ștergere -->
    <div id="deleteModal" class="modal" style="display: none;">
        <div class="modal-content">
            <h3>Confirmare Ștergere</h3>
            <p>Sigur vrei să ștergi acest produs?</p>
            <div class="modal-buttons">
                <a id="confirmDeleteBtn" href="#" class="btn-confirm-delete">Șterge</a>
                <button type="button" onclick="closeDeleteModal()" class="btn-cancel-delete">Anulează</button>
            </div>
        </div>
    </div>

    <script>
        // Deschide modalul și setează ID-ul corect pentru ștergere
        function openDeleteModal(productId) {
            const modal = document.getElementById('deleteModal');
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            
            confirmBtn.href = 'delete.php?id=' + productId;
            modal.style.display = 'flex';
        }

        // Închide modalul
        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.style.display = 'none';
        }

        // Închide la click în afara ferestrei
        window.onclick = function(event) {
            const modal = document.getElementById('deleteModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>