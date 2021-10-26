<?php
require_once('../config/database.php');

if ($_SERVER['HTTP_REFERER'] == 'http://localhost/spectacle/admin/') {
    //suppression
    if (isset($_GET['delete']) && $_GET['delete'] >= 0) {
        $id = (int)$_GET['delete'];
        $stm = $db->prepare('DELETE FROM spectacle_type WHERE id = :id');
        $stm->bindParam(':id', $id);
        $stm->execute();
    } else {

        // nettoyage
        $eventType = htmlentities($_POST['type']);
        $typeDescription = htmlentities($_POST['description']);

        //validation
        $validation = true;
        $errorNotifications = [];
        if (empty($eventType) || strlen($eventType) > 70) {
            $validation = false;
            $errorNotification['type'] = 'Champ obligatoire avec maximum 70 caractères !';
        }
        if (empty($typeDescription) || strlen($typeDescription) > 65535) {
            $validation = false;
            $errorNotifications['description'] = 'Champ  avec maximum 65535 caractères !';
        }
        //ajout base de donnée
        if ($validation) {
            $stm = $db->prepare('INSERT INTO spectacle_type ( type, description) VALUES (:type, :description)');
            $stm->bindParam(':type', $eventType);
            $stm->bindParam(':description', $typeDescription);
            $stm->execute();
        }
    }
    header('location: /spectacle/admin');
} else {
    echo $_SERVER['HTTP_REFERER'];


    header('location: /spectacle');
}
