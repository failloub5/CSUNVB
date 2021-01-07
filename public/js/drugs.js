/**
 * Auteur:
 * Date: Décembre 2020
 **/

function novaCheck(novaID, drugID, dateID) {
    divid = novaID + drugID + dateID;
    console.log(divid);
    let originalQuantity = document.getElementById(divid + "start").value;
    let currentQuantity = document.getElementById(divid + "end").value;
    if(Number(currentQuantity) !== Number(originalQuantity)) {
        document.getElementById(divid).style = "background-color: orange;"
    } else {
        document.getElementById(divid).removeAttribute("style");
    }
}

function pharmaCheck() {

}