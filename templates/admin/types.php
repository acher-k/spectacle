<?php

require_once('../config/database.php');


//affichage base de donnée
$stm = $db->query('SELECT * from spectacle_type', PDO::FETCH_ASSOC);
$types = $stm->fetchAll();
?>

<h1>Types</h1>
<div>


    <!-- tableau -->
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">type</th>
                    <th scope="col">Description</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <form action="./treatement.php" method="post">
                    <tr>
                        <td>
                            #
                        </td>
                        <td>
                            <input type="text" name="type" class="form-control" id="type" required maxlength="70">
                        </td>
                        <td>
                            <textarea class="form-control" name="description" id="description" cols="30" rows="6" maxlength="65535"></textarea>
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary">Ajouter</button>
                        </td>
                    </tr>
                </form>
                <?php
                foreach ($types as $type) {
                    echo <<<END
                        <tr>
                        <td>{$type['id']}</td>
                        <td>{$type['type']}</td>
                        <td>{$type['description']}</td>
                        <td>
                        <a href="./treatement.php?delete={$type['id']}">supprimer</a>
                        </td>
                        </tr>
                        END;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>