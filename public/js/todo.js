/**
 * Le fichier contient les fonctionnalités javascript qui ne sont utilisées que pour les tâches à réaliser
 * Auteur: Vicky Butty
 * Date: Décembre 2020
 **/

// formulaire de vérification pour todoModal
var buttons = document.querySelectorAll('.toggleTodoModal');

buttons.forEach((item) => {
    item.addEventListener('click', function (event) {
        $("#todoModal").modal("toggle");
        document.getElementById("modal-title").innerHTML = this.getAttribute("data-title");
        document.getElementById("modal-content").innerHTML = this.getAttribute("data-content");
        document.getElementById("modal-todoID").value = this.getAttribute("data-id");

        var status = this.getAttribute("data-status");
        var type = this.getAttribute("data-type");

        if(type == "2" && status == "close"){
            document.getElementById("modal-todoValue").type = "text";
        }

        document.getElementById("modal-todoType").value = type;
        document.getElementById("modal-todoStatus").value = status;

    }, false);
})
console.log(buttons.length);