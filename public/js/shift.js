/**
 * Le fichier shift.js contient les fonctionnalités javascript qui ne sont utilisées que pour la remise de garde
 * Auteur: Michael Gogniat et Paola Costa
 * Date: Décembre 2020
 **/

// formulaire de vérification pour shiftModal
var buttons = document.querySelectorAll('.toggleShiftModal');

buttons.forEach((item) => {
    item.addEventListener('click', function (event) {
        $("#shiftModal").modal("toggle");
        document.getElementById("modal-content").innerHTML = this.getAttribute("data-content");
        document.getElementById("action_id").value = this.getAttribute("data-action_id");
        document.getElementById("day").value = this.getAttribute("data-day");
        document.getElementById("shiftSheetinfo").action = this.getAttribute("data-action");
        document.getElementById("comment").type = this.getAttribute("data-comment");
    }, false);
})

$(".shiftInfo").change(function () {
    document.getElementById("updateShift").classList.remove("d-none");
});