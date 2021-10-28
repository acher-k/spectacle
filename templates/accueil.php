<?php
require_once('./config/database.php');
$dernierSpectacles = $db->query('SELECT sp.image image, sp.date_evenement date, pr.nom nom FROM spectacle sp INNER JOIN prestataire pr on pr.id=sp.prestataire_id  ORDER BY date LIMIT 3')->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container px-4 py-5">
    <h2 class="pb-2 border-bottom">Spectacle à venir</h2>

    <div class="row row-cols-1 row-cols-lg-3 align-items-stretch g-4 py-5">
        <?php
        foreach ($dernierSpectacles as $dernierSpectacle) {

            echo <<<END
            <div class="col">
                <div class="card card-cover h-100 overflow-hidden text-secondary bg-dark rounded-5 shadow-lg" style="background-image: url('./assets/image/spectacles/{$dernierSpectacle['image']}');">
                    <div class="d-flex flex-column h-100 p-5 pb-3 text-secondary text-shadow-1">
                        <h2 class="pt-5 mt-5 mb-4 display-6 lh-1 fw-bold"><a class="text-reset text-decoration-none stretched-link" href="">{$dernierSpectacle['nom']}</a></h2>
                        <p class="mt-auto">{$dernierSpectacle['date']}</p>
                    </div>
                </div>
            </div>
            END;
        }
        ?>
    </div>
</div>