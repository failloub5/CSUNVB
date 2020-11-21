<?php

ob_start();
$title = "CSU-NVB - 404";
?>

erreur : la page l'existe pas ou ne vous n'êtes pas autorisé(e)

<?php
$content = ob_get_clean();
require GABARIT;
?>
