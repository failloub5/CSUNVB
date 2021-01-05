/**
 * Auteur:
 * Date: Décembre 2020
 **/

function novaCheck(novaID, drugID, dateID) {
    console.log(novaID + drugID + dateID + "start");
    let originalQuantity = document.getElementById(novaID + drugID + dateID + "start").value;
    let currentQuantity = document.getElementById(novaID + drugID + dateID + "end").value;
    if(Number(currentQuantity) !== Number(originalQuantity)) {
        document.getElementById("nova" + novaID).style = "background-color: orange;"
    } else {
        document.getElementById("nova" + novaID).removeAttribute("style");
    }
}

function pharmaCheck() {

}