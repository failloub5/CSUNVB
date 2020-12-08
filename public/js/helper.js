function myFunction() {
    document.getElementById("demo").innerHTML = "<div class='modal fade' id='popUpValidation' data-backdrop='static' data-keyboard='false' tabindex='-1' role='dialog'>\
    <div class='modal-dialog modal-dialog-centered' role='document'>\
        <div class='modal-content'>\
            <div class='modal-header'>\
                <h4 class='modal-title'>titre</h4>\
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>\
                    <span aria-hidden='true'>x</span>\
                </button>\
            </div>\
            <div class='modal-body'>\
                <p>message</p>\
            </div>\
            <div class='modal-footer'>\
                <button type='button' class='btn btn-primary'>Ok</button>\
                <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancel</button>\
            </div>\
        </div>\
    </div>\
</div>";}