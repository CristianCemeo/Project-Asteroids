// Modal para editar usuario
function openModalUsuario(id) {
    var htmlTexto = '<form class="col s12" method="POST" action="#editar">' +
        '<div class="row">' +
        '<div class="input-field col s8 offset-s2">' +
        '<input placeholder="Nuevo nombre" id="nuevoNombre" name="nuevoNombre" type="text" class="validate white-text">' +
        '</div>' +
        
        '<div class="input-field col s8 offset-s2">' +
        '<input placeholder="Nueva contraseña" id="nuevaPass" name="nuevaPass" type="password" class="validate white-text">' +
        '</div>' +

        '<div class="input-field col s8 offset-s2">' +
        '<input placeholder="Repita contraseña" id="nuevaPass2" name="nuevaPass2" type="password" class="validate white-text">' +
        '</div>' +
        
        '<input id="idUsuario" name="idUsuario" type="hidden" value="' + id + '">' +
        
        '<div class="input-field col s12 center-align">' +
            '<button class="btn waves-effect waves-red btn-large red darken-3" type="submit" name="editarUsuario" id="editarUsuario">Aceptar' +
                '<i class="material-icons right">add</i>' +
            '</button>' +
        '</div>' +
        
        '</form>';

    // Introducimos el texto anterior en el modal
    $('#modal2 #modal_content').html(htmlTexto);

    // Inicializamos modal de Materialize
    $('.modal.editarUsuario').modal();

    // Instanciamos el modal y finalmente lo abrimos
    var instance = M.Modal.getInstance(document.getElementById("modal2"));
    instance.open();
};

// Modal para editar registro del ranking
function openModalEditar(id) {
    var htmlTexto = '<form class="col s12" method="POST" action="#editar">' +
        '<div class="row">' +
        '<div class="input-field col s8 offset-s2">' +
        '<input placeholder="Nueva puntuación" id="nuevaPuntuacion" name="nuevaPuntuacion" type="number" class="validate white-text">' +
        '</div>' +
        
        '<div class="input-field col s8 offset-s2">' +
        '<input placeholder="Nueva fecha" id="nuevaFecha" name="nuevaFecha" type="text" class="datepicker white-text">' +
        '</div>' +
        
        '<input id="idRanking" name="idRanking" type="hidden" value="' + id + '">' +
        
            '<div class="input-field col s12 center-align">' +
                '<button class="btn waves-effect waves-red btn-large red darken-3" type="submit" name="editar" id="editar">Aceptar' +
                    '<i class="material-icons right">add</i>' +
                '</button>' +
            '</div>' +
        
        '</form>';

    $('#modal1 #modal_content').html(htmlTexto);

    $('.modal.editar').modal();

    var instance = M.Modal.getInstance(document.getElementById("modal1"));
    instance.open();

    $('.datepicker').datepicker({
        container: 'body',
        format: 'dd/mm/yyyy',
        firstDay: true,
        i18n: {
            months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            weekdays: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
            weekdaysAbbrev: ['D','L','M','X','J','V','S'],
            clear: 'Limpiar',
            done: 'Aceptar',
            cancel: "Cancelar",
        }
    });

};

// Modal para confirmar borrar usuario
function openModalBorrarUsuario(id) {
    var htmlTexto = '<form class="col s12" method="POST" action="#borrar">' +
        '<div class="input-field col s12 center-align">' +
        '<p>¿Seguro de que desea borrar ese usuario?</p>' +

        '<input id="idUsuario" name="idUsuario" type="hidden" value="' + id + '">' +

        '<button class="btn waves-effect waves-red btn-large red darken-3" type="submit" name="borrarUsuario" id="borrarUsuario">Sí' +
        '</div>' +
        '</form>';

    $('#modal4 #modal_content').html(htmlTexto);

    $('.modal.borrarUsuario').modal();

    var instance = M.Modal.getInstance(document.getElementById("modal4"));
    instance.open();
};

// Modal para confirmar borrar registro del ranking
function openModalBorrar(id) {
    var htmlTexto = '<form class="col s12" method="POST" action="#borrar">' +
        '<div class="input-field col s12 center-align">' +
        '<p>¿Seguro de que desea borrar ese registro?</p>' +

        '<input id="idRanking" name="idRanking" type="hidden" value="' + id + '">' +

        '<button class="btn waves-effect waves-red btn-large red darken-3" type="submit" name="borrar" id="borrar">Sí' +
        '</div>' +
        '</form>';

    $('#modal3 #modal_content').html(htmlTexto);

    $('.modal.borrar').modal();

    var instance = M.Modal.getInstance(document.getElementById("modal3"));
    instance.open();
};