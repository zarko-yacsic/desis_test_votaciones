
// Validación de formulario votación...
var form_votacion = '#form_votacion';
$(document).on('click', form_votacion + ' button', function(){
    var nombre      = $(form_votacion + ' #txt_nombre');
    var apellido    = $(form_votacion + ' #txt_apellido');
    var alias       = $(form_votacion + ' #txt_alias');
    var rut         = $(form_votacion + ' #txt_rut');
    var email       = $(form_votacion + ' #txt_email');
    var region      = $(form_votacion + ' #sel_region');
    var comuna      = $(form_votacion + ' #sel_comuna');
    var candidato   = $(form_votacion + ' #sel_candidato');

    if($.trim(nombre.val()) == ''){
        MensajeAlertifyJS('Por favor ingresa tu nombre.', 'error', nombre);
        return false;
    }
    if($.trim(apellido.val()) == ''){
        MensajeAlertifyJS('Por favor ingresa tu apellido.', 'error', apellido);
        return false;
    }
    if($.trim(alias.val()) == ''){
        MensajeAlertifyJS('Por favor ingresa tu alias.', 'error', alias);
        return false;
    }
    if($.trim(alias.val()) != ''){
        if(alias.val().length <= 5){
            MensajeAlertifyJS('La cantidad de caracteres para tu alias debe ser mayor a 5.', 'error', alias);
            return false;
        }
        else{
            if(!/^([A-Za-z0-9])*$/.test(alias.val())){
                MensajeAlertifyJS('Tu alias sólo debe contener letras y números.', 'error', alias);
                return false;
            }
        }
    }

    if($.trim(rut.val()) == ''){
        MensajeAlertifyJS('Por favor ingresa tu RUT.', 'error', rut);
        return false;
    }

    if($.trim(rut.val()) != ''){
        var v_numero = $.trim(rut.val()).split('-')[0];
        var v_digito = $.trim(rut.val()).split('-')[1];

        if (!validarRut(parseInt(v_numero.replaceAll('.', '')), v_digito)){
            MensajeAlertifyJS('El formato del RUT ingresado no es válido.', 'error', rut);
            return false;
        }
    }

    if($.trim(email.val()) == ''){
        MensajeAlertifyJS('Por favor ingresa tu correo electrónico.', 'error', email);
        return false;
    }
    if($.trim(email.val()) != ''){
        if(!validarEmail(email.val())){
            MensajeAlertifyJS('El correo electrónico ingresado no es válido.', 'error', email);
            return false;
        }
    }

    if(region.val() == ''){
        MensajeAlertifyJS('Por favor selecciona tu región.', 'error', region);
        return false;
    }
    if(comuna.val() == ''){
        MensajeAlertifyJS('Por favor selecciona tu comuna.', 'error', comuna);
        return false;
    }
    if(candidato.val() == ''){
        MensajeAlertifyJS('Por favor selecciona tu candidato.', 'error', candidato);
        return false;
    }

    var chk_selected = 0;
    $(form_votacion + ' input[type=checkbox]:checked').each(function(){
        chk_selected++;
    });
    if(chk_selected < 2){
        MensajeAlertifyJS('Debes seleccionar al menos dos opciones en ¿Cómo se enteró de nosotros?', 'error');
        return false;
    }

    MensajeAlertifyJS('ENVIANDO DATOS, ESPERE...', 'success');
    var parametros = new FormData($(form_votacion)[0]);

    $.ajax({
        url: 'php/votaciones.php',
        type: 'POST',
        data: parametros,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function(){
            $(form_votacion + ' button').attr('disabled', 'disabled').css('background-color', '#dddddd');
        },
        success: function(data){
            var json = eval('(' + data + ')');
              
            // Archivo subido OK...
            if(json.status == 'SUCCESS'){
                Swal.fire({
                    icon: 'success',
                    title: 'FELICIDADES!',
                    text: 'Se ha guardado correctamente tu votación.'
                });
                alertify.dismissAll();
                cargarListadoVotacion();
                $(form_votacion)[0].reset();
                $(form_votacion + ' button').css('background-color', '#00579e');
                $(form_votacion + ' button').removeAttr('disabled');
            }

            // El RUT ingresado ya realizó votación...
            if(json.status == 'RUT_EXISTE'){
                MensajeAlertifyJS(json.rut_existe, 'error', rut);
                $(form_votacion + ' button').css('background-color', '#00579e');
                $(form_votacion + ' button').removeAttr('disabled');
                return false;
            }

            // Error al enviar datos de la votación ('json.error' indica tipo de error en un mensaje)...
            if(json.status == 'ERROR'){
                MensajeAlertifyJS(json.error, 'error');
                $(form_votacion + ' button').css('background-color', '#00579e');
                $(form_votacion + ' button').removeAttr('disabled');
                return false;
            }
        }
    });
});


// Seleccionar comunas según región...
$(document).on('change', form_votacion + ' #sel_region', function(){
    var region_id = $(form_votacion + ' #sel_region').val();
    obtenerListadoComunas(region_id);
});


// Seleccionar provincia y almacenar en campo oculto...
$(document).on('change', form_votacion + ' #sel_comuna', function(){
    $(form_votacion + ' #sel_comuna > option:selected').each(function(){
        if($(this).val() != ''){
            $(form_votacion + ' #hf_provincia').val($(this).data('provincia_id'));
        }
    });
});


// Cargar listado de los candidatos con su votación...
function cargarListadoVotacion(){
    $('#listado_votacion').load('php/votos.php?rnd=' + Math.floor((Math.random() * 1000) + 1));
}


// Cargar opciones en comboBox 'Región'...
function obtenerListadoRegiones(){
    var sel_region = $(form_votacion + ' #sel_region');
    $.ajax({
        url: 'php/obtenerListados.php',
        type: 'GET',
        data: {
            listado : 'regiones'
        },
        beforeSend: function (){
            $(sel_region).html('<option>Obteniendo regiones...</option>');
        },
        error: function (jqXHR, textStatus, errorThrown){
            $(sel_region).html('<option>Error al obtener regiones</option>');
            console.log(jqXHR);
        },
        success: function (response){
            $(sel_region).html(response);
        },
        complete(){
        }
    });
}


// Cargar opciones en comboBox 'Comuna'...
function obtenerListadoComunas(region_id){
    var sel_comuna = $(form_votacion + ' #sel_comuna');
    if(region_id != ''){
        $.ajax({
            url: 'php/obtenerListados.php',
            type: 'GET',
            data: {
                listado : 'comunas',
                region_id : region_id
            },
            beforeSend: function (){
                $(sel_comuna).html('<option>Obteniendo comunas...</option>');
            },
            error: function (jqXHR, textStatus, errorThrown){
                $(sel_comuna).html('<option>Error al obtener comunas</option>');
                console.log(jqXHR);
            },
            success: function (response){
                $(sel_comuna).html(response);
            },
            complete(){
            }
        });
    }
}


// Cargar opciones en comboBox 'Candidato'...
function obtenerListadoCandidatos(){
    var sel_candidato = $(form_votacion + ' #sel_candidato');
    $.ajax({
        url: 'php/obtenerListados.php',
        type: 'GET',
        data: {
            listado : 'candidatos'
        },
        beforeSend: function (){
            $(sel_candidato).html('<option>Obteniendo candidatos...</option>');
        },
        error: function (jqXHR, textStatus, errorThrown){
            $(sel_candidato).html('<option>Error al obtener candidatos</option>');
            console.log(jqXHR);
        },
        success: function (response){
            $(sel_candidato).html(response);
        },
        complete(){
        }
    });
}


// Dar formato al textbox mientras se ingresa el RUT...
function cargarFormatoRut(){
    $('.formato_rut').on({
        keyup: function (){
            let x = $(this);
            let cadena = x.val().replace("-", "");
            if (cadena.length > 10) {
                cadena = cadena.substring(0, 9);
            }
            if (cadena.length > 1) {
                let dv = cadena.substring(cadena.length - 1, cadena.length);
                let rut = cadena.substring(0, cadena.length - 1);
                if (isNaN(rut)) {
                    cadena = "";
                } else {
                    cadena = rut + "-" + dv.toUpperCase();
                }
            }
            x.val(cadena);
        },
        keypress: function (event) {
            if (event.charCode == 75 || event.charCode == 107) { //LETRA K
            } else if (event.charCode < 48 || event.charCode > 57) {//DISTITO A NUMEROS 0-9
                return false;
            }
        },
        focus: function () {
            let x = $(this);
            let cadena = x.val();
            x.val(cadena.replace(/[.]/g, ""))
        },
        blur: function (event) {
            let x = $(this);
            let cadena = x.val().split("-");
            if (cadena.length == 2){
                var monto = parseInt(cadena[0]);
                monto = new Intl.NumberFormat("de-DE").format(monto);
                monto += '-' + cadena[1];
                cadena = monto;
            }
            x.val(cadena);
        }
    });
}


// Validar el formato del RUT ingresado al enviar...
function validarRut(numero, dv){
    if (numero.length == 0 || numero.length > 9) {
        return false;
    }
    else {
        if (obtenerDV(numero) == dv.toUpperCase()) return true;
    }
    return false;
}


// obtener el dígito verificador del RUT ingresado para su validación...
function obtenerDV(numero){
    nuevo_numero = numero.toString().split('').reverse().join('');
    for (i = 0, j = 2, suma = 0; i < nuevo_numero.length; i++, ((j == 7) ? j = 2 : j++)){
        suma += (parseInt(nuevo_numero.charAt(i)) * j);
    }
    n_dv = 11 - (suma % 11);
    var dv = ((n_dv == 11) ? 0 : ((n_dv == 10) ? "K" : n_dv));
    return dv;
}


// Validación de correo electrónico...
function validarEmail(email){
    re = /^([\da-z_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/
    if (!re.exec(email)) {
        return false;
    }
    else {
        return  true;
    }
}


// Notificaciones de formulario (Basado en 'Alertify.js' (https://alertifyjs.com)...
function MensajeAlertifyJS(mensaje, tipoMensaje = 'normal', element = '', tiempoEspera = 6) {
    alertify.set('notifier','position', 'bottom-right');
    alertify.notify(mensaje, tipoMensaje, tiempoEspera);
    if(element != ''){
        $(element).focus().select();
    }
}
