<?php
session_start();
require_once('../config/database.php');
function printError(string $error)
{
    echo "<p class=\"fw-lighter\">$error</p>";
}
$descriptionErr = $_SESSION['error']['description'] ?? null;
$Err = $_SESSION['error'] ?? null;
session_destroy();

//affichage base de donnée
$stm = $db->query('SELECT * from prestataire', PDO::FETCH_ASSOC);
$prestataires = $stm->fetchAll();

//gestion d'erreur de saisie


?>

<h1>Types</h1>
<div>


    <!-- tableau -->
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Description</th>
                    <th scope="col">Image</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <form action="./treatementPrestataires.php" method="post" enctype="multipart/form-data">
                    <tr>
                        <td>
                            #
                        </td>
                        <td>
                            <input type="text" name="name" class="form-control" id="type" required maxlength="70">
                            <?php (isset($Err['name'])) ? printError($_SESSION['error']['name']) : null ?>

                        </td>
                        <td>
                            <textarea class="form-control" name="description" id="description" cols="30" rows="6" maxlength="65535"></textarea>
                            <?php (isset($Err['description'])) ? printError($_SESSION['error']['description']) : null ?>

                        </td>
                        <td>
                            <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
                            <?php (isset($Err['image'])) ? printError($_SESSION['error']['image']) : null ?>

                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary">Ajouter</button>
                        </td>
                    </tr>
                </form>
                <?php
                foreach ($prestataires as $prestataire) {
                    echo <<<END
                        <tr>
                        <td>{$prestataire['id']}</td>
                        <td>{$prestataire['nom']}</td>
                        <td>{$prestataire['description']}</td>
                        <td>
                        <img  width="150" src="../assets/image/prestataires/{$prestataire['image']}" alt="image prestataire"></td>
                        <td>
                        <a href="./treatementPrestataires.php?delete={$prestataire['id']}">supprimer</a>
                        </td>
                        </tr>
                        END;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>