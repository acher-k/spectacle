<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

<body class="d-flex">
    <?php require_once('../templates/admin/nav.php') ?>
    <main class="px-3 vh-100 w-100 overflow-auto">
        <?php if (isset($_GET['page']) && $_GET['page'] !== null && file_exists('../templates/admin/' . $_GET['page'] . '.php')) {
            require_once('../templates/admin/' . $_GET['page'] . '.php');
        } else {
            require_once('../templates/admin/spectacles.php');
        }
        ?>


    </main>

</body>

</html>