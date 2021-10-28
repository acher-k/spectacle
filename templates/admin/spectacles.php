<?php

session_start();
require_once('../config/database.php');

if (isset($_GET['modify']) && $_GET['modify'] >= 0) {
    $id = (int)$_GET['modify'];
    $toModify = $db->query("SELECT * FROM spectacle WHERE id = $id")->fetch(PDO::FETCH_ASSOC);
    $targetModification = "?modify=" . $_GET['modify'];
}

/**
 * Affiche erreur formater
 *
 * @param  mixed $error
 * @return void
 */
function printError(string $error)
{
    echo "<p class=\"fw-lighter\">$error</p>";
}
$descriptionErr = $_SESSION['error']['description'] ?? null;
$Err = $_SESSION['error'] ?? null;
session_destroy();

//affichage base de donnée
$stm = $db->query('SELECT sp.id, sa.nom nom_salle, ty.type, pr.nom pretataire_nom, sp.description, sp.date_evenement, sp.prix_ticket, sp.image
from spectacle sp 
INNER JOIN salles sa  
ON sa.id=sp.salle_id 
INNER JOIN spectacle_type ty 
ON ty.id=sp.type_id 
INNER JOIN prestataire pr 
ON pr.id=sp.prestataire_id;', PDO::FETCH_ASSOC);
$spectacles = $stm->fetchAll();
$salles = $db->query('SELECT id, nom FROM salles', PDO::FETCH_ASSOC)->fetchAll();
$types = $db->query('SELECT id, type FROM spectacle_type', PDO::FETCH_ASSOC)->fetchAll();
$prestataires = $db->query('SELECT id, nom FROM prestataire', PDO::FETCH_ASSOC)->fetchAll();

//gestion d'erreur de saisie


?>

<h1>Spectacles</h1>
<div>


    <!-- tableau -->
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">Nom salle</th>
                    <th scope="col">Type</th>
                    <th scope="col">Prestataire</th>
                    <th scope="col">Description</th>
                    <th scope="col">Date</th>
                    <th scope="col">Prix</th>
                    <th scope="col">Image</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <form action="./treatementSpectacles.php<?= (isset($targetModification)) ? $targetModification : "" ?>" method="post" enctype="multipart/form-data">
                    <tr>
                        <td>
                            #
                        </td>
                        <td>
                            <select class="form-select" name="salleName" id="salleName" required>
                                <option <?= (isset($_GET['modify'])) ? "" : "selected" ?> disabled>Salle...</option>';
                                <?php

                                foreach ($salles as $salle) {
                                    $selectedS = ($salle['id'] == $toModify['salle_id']) ? "selected" : "";
                                    echo <<<EOF
                                            <option value="{$salle['id']}" $selectedS>{$salle['nom']}</option>
                                    EOF;
                                }
                                ?>
                            </select>
                            <?php (isset($Err['salleName'])) ? printError($Err['salleName']) : null ?>

                        </td>
                        <td>
                            <select class="form-select" name="type" id="type" required>
                                <option <?= (isset($_GET['modify'])) ? "" : "selected" ?> disabled>Type...</option>';
                                <?php
                                foreach ($types as $type) {
                                    $selectedT = ($type['id'] == $toModify['type_id']) ? "selected" : "";
                                    echo <<<EOF
                                            <option  $selectedT value="{$type['id']}">{$type['type']}</option>
                                    EOF;
                                }
                                ?>
                            </select>
                            <?php (isset($Err['type'])) ? printError($Err['type']) : null ?>

                        </td>
                        <td>
                            <select class="form-select" name="prestataireName" id="prestataireName" required>
                                <option <?= (isset($_GET['modify'])) ? "" : "selected" ?> disabled>Prestataire...</option>';
                                <?php
                                foreach ($prestataires as $prestataire) {
                                    $selectedPN = ($type['id'] == $toModify['prestataire_id']) ? "selected" : "";
                                    echo <<<EOF
                                            <option $selectedPN value="{$prestataire['id']}">{$prestataire['nom']}</option>
                                    EOF;
                                }
                                ?>
                            </select>
                            <?php (isset($Err['prestataireName'])) ? printError($Err['prestataireName']) : null ?>

                        </td>
                        <td>
                            <textarea class="form-control" name="description" id="description" cols="20" rows="6" maxlength="65535" required><?= $toModify['description'] ?? null ?></textarea>
                            <?php (isset($Err['spectacleDescription'])) ? printError($Err['spectacleDescription']) : null ?>

                        </td>
                        <td>
                            <input type="datetime-local" name="date" class="form-control" id="date" value="<?= $toModify['date_evenement'] ?? null ?>" required>
                            <?php (isset($Err['spectacleDate'])) ? printError($Err['spectacleDate']) : null ?>

                        </td>
                        <td>
                            <input type="number" name="price" placeholder="70.90" id="price" step="0.01" value="<?= $toModify['prix_ticket'] ?? null ?>">
                            <?php (isset($Err['price'])) ? printError($Err['price']) : null ?>
                        </td>
                        <td>
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            <?php (isset($Err['image'])) ? printError($Err['image']) : null ?>

                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary">Ajouter</button>
                        </td>
                    </tr>
                </form>
                <?php
                foreach ($spectacles as $spectacle) {
                    echo <<<END
                        <tr>
                        <td>{$spectacle['id']}</td>
                        <td>{$spectacle['nom_salle']}</td>
                        <td>{$spectacle['type']}</td>
                        <td>{$spectacle['pretataire_nom']}</td>
                        <td>{$spectacle['description']}</td>
                        <td>{$spectacle['date_evenement']}</td>
                        <td>{$spectacle['prix_ticket']}</td>
                        <td>
                        <img  height="150" src="../assets/image/spectacles/{$spectacle['image']}" alt="Image Spectacle"></td>
                        <td>
                        <a href="./treatementSpectacles.php?delete={$spectacle['id']}">Supprimer</a>
                        <a href="./?modify={$spectacle['id']}">Modifier</a>
                        </td>
                        </tr>
                        END;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>