<?php
session_start();
require_once('../config/database.php');
if ($_SERVER['HTTP_REFERER'] == 'http://localhost/spectacle/admin/?page=salles') {
    //suppression
    if (isset($_GET['delete']) && $_GET['delete'] >= 0) {
        $id = (int)$_GET['delete'];
        $imageToDelete = $db->query("SELECT image FROM salles WHERE id = $id")->fetch(PDO::FETCH_ASSOC)['image'];
        $stm = $db->prepare('DELETE FROM salles WHERE id = :id');
        $stm->bindParam(':id', $id);
        $stm->execute();
        unlink("../assets/image/salles/$imageToDelete");
    } else {

        // nettoyage
        $salleName = htmlentities($_POST['name']);
        $salleAdresse = htmlentities($_POST['adresse']);
        $salleCity = htmlentities($_POST['city']);
        $salleCp = htmlentities($_POST['cp']);
        $sallePlaces = htmlentities($_POST['places']);
        $salleDescription = htmlentities($_POST['description']);

        //validation
        $validation = true;
        $errorNotifications = [];
        if (empty($salleName) || strlen($salleName) > 70) {
            $validation = false;
            $errorNotifications['name'] = 'Champ obligatoire avec maximum 70 caractères !';
        }
        if (empty($salleAdresse) || strlen($salleAdresse) > 100) {
            $validation = false;
            $errorNotifications['adresse'] = 'Champ obligatoire avec maximum 100 caractères !';
        }
        if (empty($salleCity) || strlen($salleCity) > 45) {
            $validation = false;
            $errorNotifications['city'] = 'Champ obligatoire avec maximum 45 caractères !';
        }
        if (empty($salleCp) || strlen($salleCp) != 5) {
            $validation = false;
            $errorNotifications['cp'] = 'Champ obligatoire avec 5 caractères !';
        }
        if (!empty($sallePlaces) && ($sallePlaces > 999999 || $sallePlaces < 0)) {
            $validation = false;
            $errorNotifications['name'] = 'Champ obligatoire compris entre 0 et 999 999 !';
        }
        if (empty($salleDescription) || strlen($salleDescription) > 65535) {
            $validation = false;
            $errorNotifications['description'] = 'Champ obligatoire avec maximum 65535 caractères !';
        }
        if (empty($_FILES['image']['name']) || $_FILES['image']['size'] > 2000000 || !str_starts_with($_FILES['image']['type'], 'image/')) {
            $errorNotifications['image'] = 'l\'image est obligatoire, ne doit pas dépasser 2 Mo';
            $validation = false;
        }
        //ajout base de donnée
        if ($validation) {
            $imageName = time() . strstr($_FILES['image']['name'], '.');
            $stm = $db->prepare('INSERT INTO salles ( nom, adresse, ville, cp, nb_place,description, image) VALUES (:nom, :adresse, :ville, :cp, :nb_place, :description, :image)');
            $stm->bindParam(':nom', $salleName);
            $stm->bindParam(':adresse', $salleAdresse);
            $stm->bindParam(':ville', $salleCity);
            $stm->bindParam(':cp', $salleCp);
            $stm->bindParam(':nb_place', $sallePlaces);
            $stm->bindParam(':description', $salleDescription);
            $stm->bindParam(':image', $imageName);
            $stm->execute();
            move_uploaded_file($_FILES['image']['tmp_name'], "../assets/image/salles/$imageName");
        }
        $_SESSION['error'] = $errorNotifications;
    }
    header('location: /spectacle/admin?page=salles');
} else {
    echo $_SERVER['HTTP_REFERER'];


    header('location: /spectacle');
}
