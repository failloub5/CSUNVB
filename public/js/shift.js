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
        $("#action_id").val( this.getAttribute("data-action_id"));
        $("#day").val( this.getAttribute("data-day"));
        $("#modal-content").html(this.getAttribute("data-content"));
        $("#shiftSheetinfo").attr('action', this.getAttribute("data-action"));
        $("#comment").prop("type",this.getAttribute("data-comment"));
    }, false);
})

$(".shiftInfo").change(function () {
    document.getElementById("updateShift").classList.remove("d-none");
});

function diplayShiftForBase() {
    var id = $("#id").val();
    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            $("#tableContent").html(this.responseText);
        }
    };
    request.open("GET", "?action=displayShift&id="+ id, true);
    request.send();
}