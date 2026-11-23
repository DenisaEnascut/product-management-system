# Product Management System

Aplicație web CRUD pentru gestionarea produselor, realizată în **PHP**, **MySQL (PDO)**, **HTML**, **CSS** și **JavaScript**.

## Funcționalități

- **CRUD complet:** Adăugarea, vizualizarea, editarea și ștergerea produselor.
- **Căutare și paginare:** Filtrare după nume și navigare paginată prin lista de produse.
- **Gestionarea imaginilor:** Încărcarea imaginilor în folderul `uploads/` și ștergerea automată a fișierelor asociate la editare/ștergere (`unlink()`).
- **Confirmare la ștergere:** Fereastră modală JavaScript înainte de ștergerea unui produs.

## Tehnologii utilizate

- PHP (PDO)
- MySQL
- HTML5, CSS3, JavaScript

## Instalare și rulare locală (XAMPP)

1. Clonează sau descarcă repository-ul în folderul `htdocs` (ex.: `C:\xampp\htdocs\product-management`).
2. Pornește **Apache** și **MySQL** din XAMPP Control Panel.
3. Creează baza de date în phpMyAdmin și importă fișierul SQL din proiect (`products.sql`).
4. Verifică și actualizează, dacă este necesar, datele de conexiune la baza de date în `db.php`.
5. Asigură-te că folderul `uploads/` există și are permisiuni de scriere.
6. Deschide în browser: `http://localhost/product-management/index.php`
