/**
 * Lógica Frontend para la Creación de Tickets (N_ticket.js)
 */

$(document).ready(function() {
    // 1. Inicializar el editor enriquecido Summernote
    $('#descripcion').summernote({
        height: 220,
        lang: 'es-ES',
        placeholder: 'Describa detalladamente la incidencia, pasos para reproducirla o el requerimiento solicitado...',
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'table', 'hr']],
            ['misc', ['fullscreen', 'codeview']]
        ]
    });

    // 2. Validación antes de enviar el formulario
    $('#ticket_form').on('submit', function(e) {
        var idCategoria = $('#id_categoria').val();
        var titulo = $.trim($('#titulo').val());
        var esVacioSummernote = $('#descripcion').summernote('isEmpty');

        if (!idCategoria || idCategoria === '') {
            e.preventDefault();
            if (typeof swal !== 'undefined') {
                swal({
                    title: "Atención",
                    text: "Por favor seleccione una categoría para el ticket.",
                    type: "warning",
                    confirmButtonClass: "btn-warning"
                });
            } else {
                alert("Por favor seleccione una categoría para el ticket.");
            }
            $('#id_categoria').focus();
            return false;
        }

        if (titulo === '') {
            e.preventDefault();
            if (typeof swal !== 'undefined') {
                swal({
                    title: "Atención",
                    text: "El título del ticket no puede estar vacío.",
                    type: "warning",
                    confirmButtonClass: "btn-warning"
                });
            } else {
                alert("El título del ticket no puede estar vacío.");
            }
            $('#titulo').focus();
            return false;
        }

        if (esVacioSummernote) {
            e.preventDefault();
            if (typeof swal !== 'undefined') {
                swal({
                    title: "Atención",
                    text: "Debe ingresar una descripción detallada del problema.",
                    type: "warning",
                    confirmButtonClass: "btn-warning"
                });
            } else {
                alert("Debe ingresar una descripción detallada del problema.");
            }
            $('#descripcion').summernote('focus');
            return false;
        }

        // Si pasa todas las validaciones, el formulario se envía al controlador
        return true;
    });
});

