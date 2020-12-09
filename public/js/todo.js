// formulaire de vérification pour todoModal

var buttons = document.querySelectorAll('.toggleTodoModal');
buttons.forEach((item) => {
    item.addEventListener('click', function (event) {
        $("#todoModal").modal("toggle");
        document.getElementById("modal-title").innerHTML = this.getAttribute("data-title");
        document.getElementById("modal-content").innerHTML = this.getAttribute("data-content");
        document.getElementById("modal-todoID").value = this.getAttribute("data-id");
        document.getElementById("modal-todoStatus").value = this.getAttribute("data-status");
    }, false);
})
console.log(buttons.length);