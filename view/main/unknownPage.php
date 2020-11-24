<?php

ob_start();
$title = "CSU-NVB - error 404";

echo "Erreur : la page n'existe pas ou vous n'êtes pas autorisé(e) à y accéder.";

$content = ob_get_clean();
require GABARIT;
?>
