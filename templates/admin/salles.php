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
$stm = $db->query('SELECT * from salles', PDO::FETCH_ASSOC);
$salles = $stm->fetchAll();

//gestion d'erreur de saisie


?>

<h1>Salles</h1>
<div>


    <!-- tableau -->
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Adresse</th>
                    <th scope="col">Ville</th>
                    <th scope="col">CP</th>
                    <th scope="col">Nb de place</th>
                    <th scope="col">Description</th>
                    <th scope="col">Image</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <form action="./treatementSalles.php" method="post" enctype="multipart/form-data">
                    <tr>
                        <td>
                            #
                        </td>
                        <td>
                            <input type="text" name="name" class="form-control" id="type" required maxlength="70">
                            <?php (isset($Err['name'])) ? printError($Err['name']) : null ?>

                        </td>
                        <td>
                            <input type="text" name="adresse" class="form-control" id="type" required maxlength="100">
                            <?php (isset($Err['adresse'])) ? printError($Err['adresse']) : null ?>

                        </td>
                        <td>
                            <input type="text" name="city" class="form-control" id="type" required maxlength="45">
                            <?php (isset($Err['city'])) ? printError($Err['city']) : null ?>

                        </td>
                        <td>
                            <input type="text" name="cp" class="form-control" id="type" required maxlength="5">
                            <?php (isset($Err['cp'])) ? printError($Err['cp']) : null ?>

                        </td>
                        <td>
                            <input type="number" name="places" class="form-control" id="type" min="0" max="999999">
                            <?php (isset($Err['places'])) ? printError($Err['name']) : null ?>

                        </td>
                        <td class="w-25">
                            <textarea class="form-control" name="description" id="description" cols="20" rows="6" maxlength="65535"></textarea>
                            <?php (isset($Err['description'])) ? printError($Err['description']) : null ?>

                        </td>
                        <td>
                            <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
                            <?php (isset($Err['image'])) ? printError($Err['image']) : null ?>

                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary">Ajouter</button>
                        </td>
                    </tr>
                </form>
                <?php
                foreach ($salles as $salle) {
                    echo <<<END
                        <tr>
                        <td>{$salle['id']}</td>
                        <td>{$salle['nom']}</td>
                        <td>{$salle['adresse']}</td>
                        <td>{$salle['ville']}</td>
                        <td>{$salle['cp']}</td>
                        <td>{$salle['nb_place']}</td>
                        <td>{$salle['description']}</td>
                        <td>
                        <img  width="150" src="../assets/image/salles/{$salle['image']}" alt="Image salle"></td>
                        <td>
                        <a href="./treatementSalles.php?delete={$salle['id']}">supprimer</a>
                        </td>
                        </tr>
                        END;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>