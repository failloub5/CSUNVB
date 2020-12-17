<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>
        <?php
        if (isset($title)) {
            echo $title;
        } else {
            echo "Page sans nom";
        }
        ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- paths are from root ( where there is index.php ) -->
    <link href="assets/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
    <link href="assets/bootstrap/dist/css/bootstrap-grid.css" rel="stylesheet">
    <link href="assets/bootstrap/dist/css/bootstrap-reboot.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/shift.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/@fortawesome/fontawesome-free/css/all.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Syncopate" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Abel" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Pontano+Sans" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Oxygen" rel="stylesheet" type="text/css">

    <script src="assets/jquery/dist/jquery.js"></script>
    <script src="assets/bootstrap/dist/js/bootstrap.js"></script>
</head>
<body>
<div class="container">
    <header>
        <div class="row banner">
            <a href="?action=home" class="col-auto">
                <img class="logo" src="assets/images/logo.png">
            </a>
            <div class="title col text-center">
                Gestion des rapports
                <?= gitBranchTag() ?>
            </div>
        </div>
        <div>
            <a href="?action=home" class="btn btn-primary m-1 float-right">Accueil</a>
            <?php if (isset($_SESSION['user'])) : ?>
                <a href="?action=disconnect" class="btn btn-primary m-1 float-right">Se déconnecter</a>
                <p>Connecté en tant que : <strong><?= $_SESSION['user']['initials'] ?></strong> à
                    <strong><?= $_SESSION['base']['name'] ?></strong>
                </p>
            <?php endif; ?>
        </div>
    </header>
</div>

<div class="container">
    <?php
    /** if $_SESSION['flashmessage'] is set, display a box with the message */
    echo getFlashMessage();
    /** display the content of the page */
    if (isset($content)) {
        echo $content;
    } else {
        echo "page vide";
    }
    ?>
</div>
</body>
</html>
