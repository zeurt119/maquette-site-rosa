<?php

define('DB_SERVER', 'photrq-blog.db.tb-hosting.com');  // Remplace par l'adresse de ton serveur de base de données
define('DB_USERNAME', 'photrq_users');    // Nom d'utilisateur de la base de données
define('DB_PASSWORD', 'Cojanerick11');     // Mot de passe de l'utilisateur
define('DB_DATABASE', 'photrq_blog');      // Nom de la base de données

// Connexion à MySQL
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Vérification de la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}
?>