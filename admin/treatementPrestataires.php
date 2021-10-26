<?php
session_start();
require_once('../config/database.php');
if ($_SERVER['HTTP_REFERER'] == 'http://localhost/spectacle/admin/?page=prestataires') {
    //suppression
    if (isset($_GET['delete']) && $_GET['delete'] >= 0) {
        $id = (int)$_GET['delete'];
        $imageToDelete = $db->query("SELECT image FROM prestataire WHERE id = $id")->fetch(PDO::FETCH_ASSOC)['image'];
        $stm = $db->prepare('DELETE FROM prestataire WHERE id = :id');
        $stm->bindParam(':id', $id);
        $stm->execute();
        unlink("../assets/image/prestataires/$imageToDelete");
    } else {

        // nettoyage
        $prestataireName = htmlentities($_POST['name']);
        $prestataireDescription = htmlentities($_POST['description']);

        //validation
        $validation = true;
        $errorNotifications = [];
        if (empty($prestataireName) || strlen($prestataireName) > 70) {
            $validation = false;
            $errorNotifications['name'] = 'Champ obligatoire avec maximum 70 caractères !';
        }
        if (!empty($prestataireDescription) && strlen($prestataireDescription) > 65535) {
            $validation = false;
            $errorNotifications['description'] = 'Champ  avec maximum 65535 caractères !';
        }
        if (empty($_FILES['image']['name']) || $_FILES['image']['size'] > 2000000 || !str_starts_with($_FILES['image']['type'], 'image/')) {
            $errorNotifications['image'] = 'l\'image est obligatoire, ne doit pas dépasser 2 Mo';
            $validation = false;
        }
        //ajout base de donnée
        if ($validation) {
            $imageName = time() . strstr($_FILES['image']['name'], '.');
            $stm = $db->prepare('INSERT INTO prestataire ( nom, description, image) VALUES (:nom, :description, :image)');
            $stm->bindParam(':nom', $prestataireName);
            $stm->bindParam(':description', $prestataireDescription);
            $stm->bindParam(':image', $imageName);
            $stm->execute();
            move_uploaded_file($_FILES['image']['tmp_name'], "../assets/image/prestataires/$imageName");
        }
        $_SESSION['error'] = $errorNotifications;
    }
    header('location: /spectacle/admin?page=prestataires');
} else {
    echo $_SERVER['HTTP_REFERER'];


    header('location: /spectacle');
}
