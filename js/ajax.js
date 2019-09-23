// Ajax para cargar la paginacion en la pestaña de editar
$('.pagination.editar li a').on('click', function(){
    // Añadimos imagen de cargando
    $('#rankingEditar').html('<div class="loading"><img src="img/cargando.gif" width="70px" height="70px"/></div>');

    // Cogemos el valor que tiene el select de ordenar
    var selectOption = $("#ordenarEditar").val();
    // Cogemos el número de la pagina que hemos clicado
    var page = $(this).attr('dataEditar');
    // Metemos las variables en un string para enviarlas por ajax
    var dataString = 'page=' + page + '&ordenar='+selectOption;

    $.ajax({
        type: "GET",
        url: "https://project-asteroids.000webhostapp.com/inc/paginacionEditar.php",
        data: dataString,
        success: function(data) {
            $('#rankingEditar').fadeIn(2000).html(data);
            // Al completar la carga, borramos la clase active de los li (solo la tendrá uno)
            $('.pagination.editar li').removeClass('active');
            // Añadimos la clase active al li seleccionado
            $('.pagination.editar li a[dataEditar="'+page+'"]').parent().addClass('active');
        }
    });
});

// Ajax para cargar la paginacion en la pestaña de borrar
$('.pagination.borrar li a').on('click', function(){
    $('#rankingBorrar').html('<div class="loading"><img src="img/cargando.gif" width="70px" height="70px"/></div>');

    var selectOption = $("#ordenarBorrar").val();
    var page = $(this).attr('dataBorrar');
    var dataString = 'page=' + page + '&ordenar='+selectOption;

    $.ajax({
        type: "GET",
        url: "https://project-asteroids.000webhostapp.com/inc/paginacionBorrar.php",
        data: dataString,
        success: function(data) {
            $('#rankingBorrar').fadeIn(2000).html(data);
            $('.pagination.borrar li').removeClass('active');
            $('.pagination.borrar li a[dataBorrar="'+page+'"]').parent().addClass('active');
        }
    });
});

// Ajax para ordenar el ranking en la pestaña de editar
$('#ordenarEditar').on('change', function(){
    $('#rankingEditar').html('<div class="loading"><img src="img/cargando.gif" width="70px" height="70px"/></div>');

    var selectOption = $(this).val();
    var page = 1;
    var dataString = 'page=' + page + '&ordenar='+selectOption;

    $.ajax({
        type: "GET",
        url: "https://project-asteroids.000webhostapp.com/inc/paginacionEditar.php",
        data: dataString,
        success: function(data) {
            $('#rankingEditar').fadeIn(2000).html(data);
            $('.pagination.editar li').removeClass('active');
            $('.pagination.editar li a[dataEditar="'+page+'"]').parent().addClass('active');
        }
    });
});

// Ajax para ordenar el ranking en la pestaña de borrar
$('#ordenarBorrar').on('change', function(){
    $('#rankingBorrar').html('<div class="loading"><img src="img/cargando.gif" width="70px" height="70px"/></div>');

    var selectOption = $(this).val();
    var page = 1;
    var dataString = 'page=' + page + '&ordenar='+selectOption;

    $.ajax({
        type: "GET",
        url: "https://project-asteroids.000webhostapp.com/inc/paginacionBorrar.php",
        data: dataString,
        success: function(data) {
            $('#rankingBorrar').fadeIn(2000).html(data);
            $('.pagination.borrar li').removeClass('active');
            $('.pagination.borrar li a[dataBorrar="'+page+'"]').parent().addClass('active');
        }
    });
});
