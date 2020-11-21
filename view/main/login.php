<?php
/**
 * Auteur: Thomas Grossmann / Mounir Fiaux
 * Date: Mars 2020
 **/

ob_start();
$title = "CSU-NVB - Login";
?>

<div class="row">
    <form action="?action=login" method="post" class="form form-group">
        <label for="initales">Initiales</label>
        <input id="initiales" type="text" class="form-group form-control" <?php if (isset($_POST['username']))echo "value ='".$_POST['username']."'"?> name="username" required>
        <label for="password">Mot de passe</label>
        <input id="password" type="password" class="form-group form-control" name="password" required>
        <label>Quel site ?</label><br>
        <div class="form-check-inline">
            <div class="form-check">
                <input id="yverdon" type="radio" name="base" <?php if (isset($_POST['base']))if($_POST['base']== 1 ) echo "checked='checked'"?> value="1" required>Yverdon
            </div>
            <div class="form-check">
                <input id="stloup" type="radio" name="base" <?php if (isset($_POST['base']))if($_POST['base']== 3 ) echo "checked='checked'"?> value="3" required>Saint-Loup
            </div>
            <div class="form-check">
                <input id="stecroix" type="radio" name="base" <?php if (isset($_POST['base']))if($_POST['base']== 2 ) echo "checked='checked'"?> value="2" required>Sainte-Croix
            </div>
            <div class="form-check">
                <input id="valleejoux" type="radio" name="base" <?php if (isset($_POST['base']))if($_POST['base']== 5 ) echo "checked='checked'"?> value="5" required>Vallée-de-Joux
            </div>
            <div class="form-check">
                <input id="payerne" type="radio" name="base" <?php if (isset($_POST['base']))if($_POST['base']== 4 ) echo "checked='checked'"?> value="4" required>Payerne
            </div>
        </div><br><br>
        <button type="submit" id="btnLogin" class="btn btn-primary">Connecter</button>
    </form>
</div>

<?php
$content = ob_get_clean();
require GABARIT;
?>
