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
    }, false);
})
console.log(buttons.length);