/**
 * Auteur:
 * Date: Décembre 2020
 **/

function novaCheck(novaID) {
    let originalQuantity = document.getElementById("nova" + novaID + "start").value;
    let currentQuantity = document.getElementById("nova" + novaID + "end").value;
    if(Number(currentQuantity) != Number(originalQuantity)) {
        document.getElementById("nova" + novaID).style = "background-color: red;"
    } else {
        document.getElementById("nova" + novaID).removeAttribute("style");
    }
}

function pharmaCheck() {

}