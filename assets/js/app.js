$(document).ready(function () {
    delete_user();
});

/**
 * https://sweetalert2.github.io/#download
 * Link in Zwischenablage kopieren
 * @param element
 */
function copyToClipboard(element) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(element).val()).select();
    document.execCommand("copy");
    $temp.remove();
}

/**
 * Benuteraccount löschen
 */
function delete_user() {
    // delete user Button (außerhalb des Formulars) beim klick
    $('#delete_user_btn').on('click', function (e) {
        // SweetAlert zeigen
        Swal.fire({
            title: 'Benutzeraccount löschen?',
            text: 'Bist du dir sicher, den Benutzeraccount zu löschen? Dieser Schritt kann nicht mehr rückgängig gemacht werden!',
            icon: 'question',
            showCloseButton: true,
            showCancelButton: true,
            focusConfirm: false,
            confirmButtonColor: '#41d630',
            cancelButtonColor: '#d33',
            confirmButtonText: '<i class="fa fa-thumbs-up"></i> Löschen!',
            confirmButtonAriaLabel: 'Löschen',
            cancelButtonText: 'Abbrechen',
            cancelButtonAriaLabel: 'Abbrechen'
        }).then((result) => { // Confirm button wurde geklickt
            if (result.value) {
                // Formular abschicken durch Btn klick
                $('#delete_user_final').click();
            }
        })
    });
}
