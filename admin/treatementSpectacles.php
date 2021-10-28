<?php
session_start();
require_once('../config/database.php');
if ($_SERVER['HTTP_REFERER'] == 'http://localhost/spectacle/admin/' || str_starts_with($_SERVER['HTTP_REFERER'], 'http://localhost/spectacle/admin/?modify=')) {
    //suppression
    if (isset($_GET['delete']) && $_GET['delete'] >= 0) {
        $id = (int)$_GET['delete'];
        $imageToDelete = $db->query("SELECT image FROM spectacle WHERE id = $id")->fetch(PDO::FETCH_ASSOC)['image'];
        $stm = $db->prepare('DELETE FROM spectacle WHERE id = :id');
        $stm->bindParam(':id', $id);
        $stm->execute();
        unlink("../assets/image/spectacles/$imageToDelete");
    } else {

        // nettoyage
        $salleNameId = (int)$_POST['salleName'];
        $typeId = (int)$_POST['type'];
        $prestataireNameId = (int)$_POST['prestataireName'];
        $spectacleDescription = htmlentities($_POST['description']);
        $spectacleDate = htmlentities($_POST['date']);
        $spectaclePrice = (float)$_POST['price'];

        //validation
        $validation = true;
        $errorNotifications = [];
        if ($salleNameId < 0 || empty($salleNameId)) {
            $validation = false;
            $errorNotifications['salleName'] = 'Champ obligatoire !';
        }
        if ($typeId < 0 || empty($typeId)) {
            $validation = false;
            $errorNotifications['type'] = 'Champ obligatoire !';
        }
        if ($prestataireNameId < 0 || empty($prestataireNameId)) {
            $validation = false;
            $errorNotifications['prestataireName'] = 'Champ obligatoire !';
        }
        if (empty($spectacleDescription) || strlen($spectacleDescription) > 65535) {
            $validation = false;
            $errorNotifications['spectacleDescription'] = 'Champ obligatoire avec maximum 65535 caractères !';
        }
        if (!preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}/', $spectacleDate)) {
            $validation = false;
            $errorNotifications['spectacleDate'] = 'Format demander \'YYYY-MM-DDThh:mm\' !';
        }
        if ($spectaclePrice < 0 || $spectaclePrice > 999.99) {
            $validation = false;
            $errorNotifications['price'] = 'Champ obligatoire compris entre 0 et 999.99 !';
        }
        if (isset($_GET['modify']) && $_GET['modify'] >= 0) {
            if (!empty($_FILES['image']['name'])) {
                if ($_FILES['image']['size'] > 2000000 || !str_starts_with($_FILES['image']['type'], 'image/')) {
                    $errorNotifications['image'] = 'l\'image est obligatoire, ne doit pas dépasser 2 Mo';
                    $validation = false;
                } else {
                    $imageName = time() . strstr($_FILES['image']['name'], '.');
                }
            } else {
                $imageName = $db->query('SELECT image FROM spectacle')->fetch(PDO::FETCH_ASSOC)['image'];
            }
        } elseif (empty($_FILES['image']['name']) || $_FILES['image']['size'] > 2000000 || !str_starts_with($_FILES['image']['type'], 'image/')) {
            $errorNotifications['image'] = 'l\'image est obligatoire, ne doit pas dépasser 2 Mo';
            $validation = false;
        } else {
            $imageName = time() . strstr($_FILES['image']['name'], '.');
        }

        if ($validation) {
            if (isset($_GET['modify']) && $_GET['modify'] >= 0) {
                $id = $_GET['modify'];
                $stm = $db->prepare("UPDATE `spectacle`
                SET  `salle_id` = :salle_id,
                `type_id` = :type_id,
                `prestataire_id` = :prestataire_id,
                `description` = :description,
                `date_evenement` = :date_evenement,
                `prix_ticket` = :prix_ticket,
                `image` = :image
                WHERE `spectacle`.`id` = :id ");

                $stm->bindParam(':id', $id, PDO::PARAM_INT);
            } else {
                $stm = $db->prepare('INSERT INTO `spectacle` (`id`, `salle_id`, `type_id`, `prestataire_id`, `description`, `date_evenement`, `prix_ticket`, `image`) VALUES (NULL, :salle_id, :type_id, :prestataire_id, :description, :date_evenement, :prix_ticket, :image)');
            }
            $stm->bindParam(':salle_id', $salleNameId, PDO::PARAM_INT);
            $stm->bindParam(':type_id', $typeId, PDO::PARAM_INT);
            $stm->bindParam(':prestataire_id', $prestataireNameId, PDO::PARAM_INT);
            $stm->bindParam(':description', $spectacleDescription, PDO::PARAM_STR);
            $stm->bindParam(':date_evenement', $spectacleDate, PDO::PARAM_STR);
            $stm->bindParam(':prix_ticket', $spectaclePrice, PDO::PARAM_STR);
            $stm->bindParam(':image', $imageName, PDO::PARAM_STR);
            $stm->execute();
            if (isset($imageName)) {

                move_uploaded_file($_FILES['image']['tmp_name'], "../assets/image/spectacles/$imageName");
            }
        }
        $_SESSION['error'] = $errorNotifications;
    }
    header('location: /spectacle/admin');
} else {
    echo $_SERVER['HTTP_REFERER'];


    header('location: /spectacle');
}
