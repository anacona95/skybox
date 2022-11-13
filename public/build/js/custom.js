/**
 * Resize function without multiple trigger
 * 
 * Usage:
 * $(window).smartresize(function(){  
 *     // code here
 * });
 */
(function ($, sr) {
    // debouncing function from John Hann
    // http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
    var debounce = function (func, threshold, execAsap) {
        var timeout;

        return function debounced() {
            var obj = this, args = arguments;
            function delayed() {
                if (!execAsap)
                    func.apply(obj, args);
                timeout = null;
            }

            if (timeout)
                clearTimeout(timeout);
            else if (execAsap)
                func.apply(obj, args);

            timeout = setTimeout(delayed, threshold || 100);
        };
    };

    // smartresize 
    jQuery.fn[sr] = function (fn) {
        return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr);
    };

})(jQuery, 'smartresize');
/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var CURRENT_URL = window.location.href.split('#')[0].split('?')[0],
    $BODY = $('body'),
    $MENU_TOGGLE = $('#menu_toggle'),
    $SIDEBAR_MENU = $('#sidebar-menu'),
    $SIDEBAR_FOOTER = $('.sidebar-footer'),
    $LEFT_COL = $('.left_col'),
    $RIGHT_COL = $('.right_col'),
    $NAV_MENU = $('.nav_menu'),
    $FOOTER = $('footer');


//variable para el total en ordenes
var valtotal;
var valtotalSeguro;
var valorTotalOrden;
var descuentoOrden;
var envioOrden;
var tarifa_especial;
var impuestosOrden;
var datatables;
// Sidebar
function init_sidebar() {
    // TODO: This is some kind of easy fix, maybe we can improve this
    var setContentHeight = function () {
        // reset height
        $RIGHT_COL.css('min-height', $(window).height());

        var bodyHeight = $BODY.outerHeight(),
            footerHeight = $BODY.hasClass('footer_fixed') ? -10 : $FOOTER.height(),
            leftColHeight = $LEFT_COL.eq(1).height() + $SIDEBAR_FOOTER.height(),
            contentHeight = bodyHeight < leftColHeight ? leftColHeight : bodyHeight;

        // normalize content
        contentHeight -= $NAV_MENU.height() + footerHeight;

        $RIGHT_COL.css('min-height', contentHeight);
    };

    $SIDEBAR_MENU.find('a').on('click', function (ev) {
        console.log('clicked - sidebar_menu');
        var $li = $(this).parent();

        if ($li.is('.active')) {
            $li.removeClass('active active-sm');
            $('ul:first', $li).slideUp(function () {
                setContentHeight();
            });
        } else {
            // prevent closing menu if we are on child menu
            if (!$li.parent().is('.child_menu')) {
                $SIDEBAR_MENU.find('li').removeClass('active active-sm');
                $SIDEBAR_MENU.find('li ul').slideUp();
            } else {
                if ($BODY.is(".nav-sm")) {
                    $SIDEBAR_MENU.find("li").removeClass("active active-sm");
                    $SIDEBAR_MENU.find("li ul").slideUp();
                }
            }
            //$li.addClass('active');

            $('ul:first', $li).slideDown(function () {
                setContentHeight();
            });
        }
    });

    // toggle small or large menu 
    $MENU_TOGGLE.on('click', function () {
        console.log('clicked - menu toggle');

        if ($BODY.hasClass('nav-md')) {
            document.cookie = "menu_toggle=0;";
            $SIDEBAR_MENU.find('li.active ul').hide();
            $SIDEBAR_MENU.find('li.active').addClass('active-sm').removeClass('active');
        } else {
            document.cookie = "menu_toggle=1;";
            $SIDEBAR_MENU.find('li.active-sm ul').show();
            $SIDEBAR_MENU.find('li.active-sm').addClass('active').removeClass('active-sm');
        }

        $BODY.toggleClass('nav-md nav-sm');
        setContentHeight();
    });

    // check active menu
    $SIDEBAR_MENU.find('a[href="' + CURRENT_URL + '"]').parent('li').addClass('current-page');

    $SIDEBAR_MENU.find('a').filter(function () {
        return this.href == CURRENT_URL;
    }).parent('li').addClass('current-page').parents('ul').slideDown(function () {
        setContentHeight();
    }).parent().addClass('active');

    // recompute content when resizing
    $(window).smartresize(function () {
        setContentHeight();
    });

    setContentHeight();

    // fixed sidebar
    if ($.fn.mCustomScrollbar) {
        $('.menu_fixed').mCustomScrollbar({
            autoHideScrollbar: true,
            theme: 'minimal',
            mouseWheel: { preventDefault: true }
        });
    }
}


$(document).ready(function () {
    init_sidebar();
    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '< Ant',
        nextText: 'Sig >',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };

    $.datepicker.setDefaults($.datepicker.regional['es']);
    $(function () {
        $("#calendar-prealerta").datepicker({
            minDate: "+2D"
        });
    });
    $(function () {
        $(".data-table").dataTable({
            language: {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Entradas",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
        });
    });
    $(function () {
        $(".calendar").datepicker();
    });
    $(function () {
        $("#cumple").datepicker({
            maxDate: "-18Y",
            changeYear: true,
            changeMonth: true,
            dateFormat: 'yy-mm-dd'
        });
    });
  
    if($(".prealertaCsv").length > 0){
        $(".prealertaCsv").datetimepicker();
    }
    
    if ($(".money").length > 0) {
        new AutoNumeric.multiple(".money", {
            currencySymbol: "$ ",
            decimalCharacter: ",",
            digitGroupSeparator: ".",
            decimalPlaces: 0,
            minimumValue: 0
        });

    }

    if ($(".usd").length > 0) {
        new AutoNumeric.multiple(".usd", {
            currencySymbol: "$ ",
            decimalCharacter: ".",
            decimalPlaces: 1,
            minimumValue: 0
        });

    }
    if ($("#valTotal").length > 0) {
        valtotal = new AutoNumeric("#valTotal", {
            currencySymbol: "$ ",
            decimalCharacter: ",",
            digitGroupSeparator: ".",
            decimalPlaces: 0,
            readOnly: true,
            minimumValue: 0
        });
    }

    if ($("#seguro").length > 0) {
        valtotalSeguro = new AutoNumeric("#seguro", {
            currencySymbol: "$ ",
            decimalCharacter: ",",
            digitGroupSeparator: ".",
            decimalPlaces: 0,
            readOnly: true,
            minimumValue: 0
        });
    }
    if ($("#valorTotalOrden").length > 0) {
        valorTotalOrden = new AutoNumeric("#valorTotalOrden", {
            currencySymbol: "$ ",
            decimalCharacter: ",",
            digitGroupSeparator: ".",
            decimalPlaces: 0,
            readOnly: true,
            minimumValue: 0
        });
    }
    if ($("#descuento_orden").length > 0) {
        
        descuentoOrden = new AutoNumeric("#descuento_orden", {
            currencySymbol: "$ ",
            decimalCharacter: ",",
            digitGroupSeparator: ".",
            decimalPlaces: 0,
            minimumValue: 0
        });

        if(descuentoOrden.getNumber()!=0){
            descuentoOrden.update({ readOnly: true });
        }

    }

    if ($("#impuestos_orden").length > 0) {
        impuestosOrden = new AutoNumeric("#impuestos_orden", {
            currencySymbol: "$ ",
            decimalCharacter: ",",
            digitGroupSeparator: ".",
            decimalPlaces: 0,
            minimumValue: 0
        });

    }

    //envioOrden
    if ($("#envio_nacional").length > 0) {
        envioOrden = new AutoNumeric("#envio_nacional", {
            currencySymbol: "$ ",
            decimalCharacter: ",",
            digitGroupSeparator: ".",
            decimalPlaces: 0,
            minimumValue: 0
        });

    }
    /* tarifa_especial */
    if ($("#tarifa_especial").length > 0) {
        tarifa_especial = new AutoNumeric("#tarifa_especial", {
            currencySymbol: "$ ",
            decimalCharacter: ".",
            decimalPlaces: 2,
            minimumValue: 0,
        });
    }

    $(function () {
        $('#notifications').popover({
            placement: "bottom",
            title: "Nuevas pre-alertas",
            html: true,
            content: function () {
                return getPrealerts();
            },
        });
    });
    $("#agregarPaquetes").submit(function () {
        if ($("input:checkbox.addPaquete").filter(':checked').length < 1) {
            alert("Debe seleccionar al menos un artículo.");
            return false;
        }
    });
    if ($(".popovers").length > 0) {
        $('.popovers').popover();
    }

    if ($(".select2").length > 0) {
         $('.select2').select2();
    }

    $('#correoCali').on('submit', function(e){
        var $form = $(this);
     
        // Iterate over all checkboxes in the table
        datatables.$('input[type="checkbox"]').each(function(){
           // If checkbox doesn't exist in DOM
           if(!$.contains(document, this)){
              // If checkbox is checked
              if(this.checked){
                 // Create a hidden element 
                 $form.append(
                    $('<input>')
                       .attr('type', 'hidden')
                       .attr('name', this.name)
                       .val(this.value)
                 );
              }
           } 
        });          
     });

     if($("#frmCardTransaction").length > 0){
         $("#frmCardTransaction").validate();
     }


});

function dspInput(e) {
    $(e).children('p').hide();
    $(e).children('input').show();
    $(e).children('input').focus().select();
}

function sendInput(e) {
    var articulo_id = $(e).data('rowId');
    var valor = $(e).val();
    var type = $(e).data('type');
    var url = $("#constantes").data('url');
    $.ajax({
        data: { articulo_id: articulo_id, valor: valor, type: type },
        url: url,
        type: "post",
        success: function () {
            $(e).siblings('p').text($(e).val());
            $(e).siblings('p').show();
            $(e).hide();
        }
    });

}

function marcar(source) {

    var valorTotal = $("#constantes").data('valTotal');
    var valorSeguro = $("#constantes").data('totalSeguro');
    var valor_total_orden = $("#constantes").data('valorTotalOrden');
    var librasTotal = $("#constantes").data('totalLibras');
    var descuento = $("#constantes").data('descuento');
    var envio = envioOrden.getNumber();
    checkboxes = datatables.$('input[type="checkbox"]'); //obtenemos todos los controles del tipo Input
    for (i = 0; i < checkboxes.length; i++) //recoremos todos los controles
    {
        if (checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
        {
            checkboxes[i].checked = source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
        }
    }

    if ($(source).is(":checked")) {
        valtotal.set(valorTotal);
        valtotalSeguro.set(valorSeguro);
        valorTotalOrden.set(valor_total_orden - descuento + envio);
        descuentoOrden.set(descuento);
        

        $("#valTotal").data('valor', valorTotal);
        $("#seguro").data('valor', valorSeguro);
        $("#valorTotalOrden").data('valor', valor_total_orden - descuento + envio);
        $("#librasTotal").data('libras', librasTotal);
        $("#librasTotal").val(librasTotal);
    } else {
        valtotal.set(0);
        valtotalSeguro.set(0);
        valorTotalOrden.set(0);
        descuentoOrden.set(0);

        $("#valTotal").data('valor', 0);
        $("#seguro").data('valor', 0);
        $("#valorTotalOrden").data('valor', 0);
        $("#librasTotal").data('libras', 0);
        $("#librasTotal").val(0);
        calcularTotal();
    }

}

function markAll(element) {
    var checks = $(".all");
    for (i = 0; i < checks.length; i++) //recoremos todos los controles
    {
        if (checks[i].type == "checkbox") //solo si es un checkbox entramos
        {
            checks[i].checked = element.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
        }
    }
}

function markPrealert(element) {
    var checks = $(".prealerts");
    for (i = 0; i < checks.length; i++) //recoremos todos los controles
    {
        if (checks[i].type == "checkbox") //solo si es un checkbox entramos
        {
            checks[i].checked = element.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
        }
    }
}


function getPrealerts() {
    var html = "No hay pre-alertas nuevas";
    var url = $("#constants").data('url');
    $.ajax({
        url: url,
        async: false,
        success: function (result) {
            html = result;
            $("#newsPrealerts").text('0');
        }
    });
    return html;

}
var flete = "";
function findArticle(e) {
    if (e.keyCode != 13) { return true }
    var tracking = $("#tracking").val();
    var url = $("#constantes").data('url');
    var trm = $("#constantes").data('trm');
    var tarifa = $("#constantes").data('tarifa');
    $.ajax({
        url: url,
        data: { tracking, tracking },
        dataType: "JSON",
        beforeSend: function () {
            cleanSpan();
            $("#flete_service").val(0);
            $("#valor_tarifa").val(0);
            $("#info").addClass("hide");
            $("#panel-footer").addClass("hide");
            $("#loading").removeClass("hide");
            $("#msg-error").addClass("hide");

        },
        success: function (response) {
            if (!response.response) {
                $("#loading").addClass("hide");
                $("#msg-error").removeClass("hide");
                $("#flete_service").val(0);
                $("#valor_tarifa").val(0);
            } else {
                $("#loading").addClass("hide");
                $("#info").removeClass("hide");
                $("#panel-footer").removeClass("hide");

                $("#articulo").text(response.nombre);
                $("#cliente").text(response.cliente);
                $("#fecha_entrega").text(response.fecha_entrega);

                flete = new AutoNumeric("#flete", {
                    currencySymbol: "$ ",
                    decimalCharacter: ",",
                    digitGroupSeparator: ".",
                    decimalPlaces: 0
                });
                
                if(response.valor_tarifa!=""){
                    flete.set(trm*response.valor_tarifa);
                    AutoNumeric.set("#tarifa_especial",response.valor_tarifa);
                    $("#constantes").data('tarifa',response.valor_tarifa);
                }else{
                    flete.set(trm*tarifa);
                    AutoNumeric.set("#tarifa_especial",tarifa);
                }

                if(response.valor_tarifa_comercial!=""){
                    $("#constantes").data('tarifaManejo',response.valor_tarifa_comercial);
                }
               
                $("#flete_service").val(response.valor);
                $("#peso").val(1);
                $("#seguro").text(response.seguro);
                $("#articulo_id").val(response.articulo_id);
                $("#valor_paquete").text(response.valor_paquete);
                $("#ciudad").text(response.ciudad);
                $("#user_id").val(response.user_id);

            }
        }
    });

    return true;

}

function findArticleMiami(e) {
    if (e.keyCode != 13) { return true }
    var tracking = $("#tracking").val();
    var url = $("#constantes").data('url');
    $.ajax({
        url: url,
        data: { tracking, tracking },
        dataType: "JSON",
        async: false,
        beforeSend: function () {
            cleanSpan();
            $("#info").addClass("hide");
            $("#panel-footer").addClass("hide");
            $("#loading").removeClass("hide");
            $("#msg-error").addClass("hide");

        },
        success: function (response) {
            if (!response.response) {
                $("#loading").addClass("hide");
                $('#nuevoPaquete').modal();
                $("#track").val(tracking);
                $("#hideTrack").val(tracking);
            } else {
                $("#loading").addClass("hide");
                $("#info").removeClass("hide");
                $("#panel-footer").removeClass("hide");

                $("#articulo").text(response.nombre);
                $("#cliente").text(response.cliente);
                $("#fecha_entrega").text(response.fecha_entrega);
                $("#flete").val(response.valor);
                $("#peso").val(response.peso);
                $("#seguro").text(response.seguro);
                $("#descripcion").text(response.descripcion);
                $("#pais").text(response.pais);
                $("#ciudad").text(response.ciudad);
                $("#direccion").text(response.direccion);
                $("#articulo_id").val(response.articulo_id);
                $("#user_id").val(response.user_id);

            }
        }
    });

    return true;

}

function cleanSpan() {
    $("#articulo").text("");
    $("#cliente").text("");
    $("#fecha_entrega").text("");
    $("#flete").val("");
    $("#peso").val("");
    $("#seguro").text("");
    $("#descripcion").text("");
    $("#pais").text("");
    $("#ciudad").text("");
    $("#direccion").text("");
    $("#articulo_id").val("");
    $("#user_id").val("");
}

function getOrdenes(id) {
    var url = $("#constantes").data('url');
    var user_id = id;
    $.ajax({
        url: url,
        data: { id, id },
        dataType: "html",
        async: false,
        success: function (response) {
            $("#modal-body").html(response);
        }
    });
    $("#myModal").modal();
}

function enableButton() {
    $("#btn-modal").removeAttr('disabled');
}

function puntosDescuento(element) {
    var puntos = $(element).find(":selected").val();
    var descuento = "$ 10.000";
    var desc = "10000";
    if (puntos == "20") {
        descuento = "$ 20.000";
        desc = "20000";
    }
    if (puntos == "30") {
        descuento = "$ 30.000";
        desc = "30000";
    }
    if (puntos == "40") {
        descuento = "$ 40.000";
        desc = "40000";
    }
    if (puntos == "50") {
        descuento = "$ 50.000";
        desc = "50000";
    }
    $("#descuento-span").text(descuento);
    $("#descuento").val(desc);
    $("#puntos_select").val(puntos);
}

function setTotal(element) {

    var valor = $(element).data('valor');
    var seguro = $(element).data('seguro');
    var total = $("#valTotal").data('valor');
    var totalSeguro = $("#seguro").data('valor');
    var valor_total_orden = $("#valorTotalOrden").data('valorTotalOrden');
    var peso = $(element).data('libras');
    var total_libras = $("#librasTotal").data('libras')
    var cupon_tipo = $("#constantes").data('cuponTipo');
    var cupon_valor = $("#constantes").data('cuponValor');
    var descuento_constant = $("#constantes").data('descuento');
    var result = 0;
    var resultSeguro = 0;
    var total_orden = 0;
    var descuento_actual = descuentoOrden.getNumber();

    if ($(element).is(":checked")) {
        result = total + valor;
        resultSeguro = totalSeguro + seguro;
        total_orden = (valor_total_orden + valor + seguro);
        total_libras = total_libras + peso;

    } else {
        result = total - valor;
        resultSeguro = totalSeguro - seguro;
        total_orden = valor_total_orden - seguro - valor;
        total_libras = total_libras - peso;
        
    }

    if(cupon_tipo===1){
        descuento = (result*cupon_valor)/100;
        total_orden = (total_orden+descuento_actual)-descuento;
        descuentoOrden.set(descuento);
    }

    if(cupon_tipo===2){
        descuentoOrden.set(descuento_constant);
        total_orden = total_orden-descuento_constant;

    }

    if (total_orden < 0) {
        total_orden = 0;
    }
    $("#valTotal").data('valor', result);
    $("#seguro").data('valor', resultSeguro);
    $("#valorTotalOrden").data('valorTotalOrden', total_orden);
    $("#librasTotal").data('libras', total_libras);
    $("#librasTotal").val(total_libras);
    valtotal.set(result);
    valtotalSeguro.set(resultSeguro);
    valorTotalOrden.set(total_orden);
    calcularTotal();


}

function dellArticulosAll() {
    if (confirm("¿Está seguro de eliminar multiple?")) {
        $("#form-all").attr('action', $("#constantes").data('urlForm') + "Admin/delMultiple");
        $("#form-all").submit();

    }
    return false;
}

function dellArticulosPrealert() {
    if (confirm("¿Está seguro de eliminar multiple?")) {
        $("#form-prealert").attr('action', $("#constantes").data('urlForm') + "Admin/delMultiple");
        $("#form-prealert").submit();

    }
    return false;
}

function updArticulosAll() {
    if (confirm("¿Está seguro de cambiar el estado?")) {
        $("#form-all").attr('action', $("#constantes").data('urlForm') + "Admin/estadosCambiar");
        $("#form-all").submit();

    }
    return false;
}

function updArticulosPrealert() {
    if (confirm("¿Está seguro de cambiar el estado?")) {
        $("#form-prealert").attr('action', $("#constantes").data('urlForm') + "Admin/estadosCambiar");
        $("#form-prealert").submit();

    }
    return false;
}

function setFormEdit(element) {
    var nombre = $(element).data('nombre');
    var email = $(element).data('email');
    var rol = $(element).data('rol');
    var userId = $(element).data('userId');
    $("#nombreEdit").val(nombre);
    $("#emailEdit").val(email);
    $("#userId").val(userId);
    $('#rolEdit option[value="' + rol + '"]').attr("selected", "selected");
}

function setIdPass(element) {
    var userId = $(element).data('userId');
    $("#userIdPass").val(userId);
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function validarSeguro() {

    var valor = $("#valor").val();
    var seguroMax = $("#constantes").data("seguroMax");
    var seguroMin = $("#constantes").data("seguroMin");

    if (valor <= seguroMin-1) {
        $("#seguro_pre").attr('disabled', 'disabled');
        $("#seguro_pre").prop("checked", false);
    }
    if (valor >= seguroMin) {
        $("#seguro_pre").removeAttr('disabled');
        $("#seguro_pre").prop("checked", false);
    }
    if (valor >= seguroMax+1) {
        $("#seguro_pre").attr('disabled', 'disabled');
        $("#seguro_pre").prop("checked", true);
    }
    if ($("#tv").is(':checked')) {
        $("#seguro_pre").attr('disabled', 'disabled');
        $("#seguro_pre").prop("checked", true);
    }
}

function calcular() {
    var peso = $("#peso").val();
    var valor = $("#valor").val();
    var destino = $("#ciudad").val();
    var tecnologia = $("input[name=tecnologia]:checked", "#tecnologia").val();
    var url = $("#constantes").data('url');
    var seguro = 0;
    if ($("#seguro_pre").is(':checked')) {
        seguro = 1;
    }
    $.ajax({
        url: url,
        type: "post",
        data: { peso: peso, valor: valor, destino: destino, seguro: seguro, tecnologia: tecnologia },
        dataType: "JSON",
        beforeSend: function () {
            $("#totales").addClass("hide");
            $("#valor_flete").text();
            $("#valor_seguro").text();
            $("#valor_total").text();
            $("#tarifa").text();
            $("#libra_fraccion").text();
            $("#loading").removeClass("hide");
        },
        success: function (response) {
            $("#loading").addClass("hide");
            $("#totales").removeClass("hide");
            $("#valor_flete").text(response.valor_flete);
            $("#valor_seguro").text(response.valor_seguro);
            $("#valor_total").text(response.valor_total);
            $("#tarifa").text(response.tarifa);
            $("#libra_fraccion").text(response.libra_fraccion);
        }
    });
}

function marcarSeguro() {
    if ($("#tv").is(':checked')) {
        $("#seguro_pre").attr('disabled', 'disabled');
        $("#seguro_pre").prop("checked", true);
    } else {
        $("#seguro_pre").attr('disabled', 'disabled');
        $("#seguro_pre").prop("checked", false);
        validarSeguro();
    }
}

function setFleteIngreso(element) {

    var tarifa_4 = $("#constantes").data('tarifaCelular');
    var tarifa_5 = $("#constantes").data('tarifaComputador');
    var tarifa_minima = $("#constantes").data('tarifaMinima');
    var tarifa_manejo = $("#constantes").data('tarifaManejo');
    var trm = $("#constantes").data('trm');
    var tarifa = $("#constantes").data("tarifa");
    var peso = $("#peso").val();
    var cantidad = $("#cantidad").val();

    if ($("#comercial").is(":checked")) {
        $("#divCantidad").addClass("hidden");
        flete.set((Math.round(tarifa_manejo * trm) * peso));
        tarifa_especial.set(tarifa_manejo);
        return false;
    }
    if ($("#regular").is(":checked")) {
        $("#divCantidad").addClass("hidden");
        flete.set((Math.round(tarifa * trm) * peso));
        tarifa_especial.set(tarifa);
        return false;
    }
    if ($("#celular").is(":checked")) {
        $("#divCantidad").removeClass("hidden");
        flete.set(((Math.round(tarifa_4 * trm)) * cantidad));
        tarifa_especial.set(tarifa_4);
        return false;
    }
    if ($("#pc").is(":checked")) {
        $("#divCantidad").removeClass("hidden");
        flete.set(((Math.round(tarifa_5 * trm)) * cantidad));
        tarifa_especial.set(tarifa_5);
        return false;
    }

}

function setFleteIngresoManejo(element) {
    var peso = $("#peso").val();
    var tarifa_4 = $("#constantes").data('tarifaCelular');
    var tarifa_5 = $("#constantes").data('tarifaComputador');
    var tarifa_minima = $("#constantes").data('tarifaMinima');
    var tarifa_manejo = $("#constantes").data('tarifaManejo');

    var trm = $("#constantes").data('trm');

    if ($(element).is(":checked")) {
        flete.set(tarifa_manejo * peso);
    } else {
        if ($("#celular").is(":checked")) {
            flete.set(tarifa_4 * trm);
        }

        if ($("#pc").is(":checked")) {
            flete.set(tarifa_5 * trm);
        }

        if ($("#tv").is(":checked") || $("#ninguno").is(":checked")) {
            var tarifaEspecial = tarifa_especial.getNumber();
            if (tarifaEspecial >= tarifa_minima) {
                valor_tarifa = Math.round(tarifaEspecial * trm);
            }
            flete.set(valor_tarifa * peso);
        }
    }
}

function reLiquidarIngreso() {
    var peso = $("#peso").val();
    var trm = $("#constantes").data('trm');
    var tarifa_minima = $("#constantes").data('tarifaMinima');
    /* if (!$("#ninguno").is(':checked')) {
        return false;
    } */
    /* tarifa_especial */
    var tarifaEspecial = tarifa_especial.getNumber();
    if (tarifaEspecial >= tarifa_minima) {
        valor_tarifa = Math.round(tarifaEspecial * trm)*peso;
        flete.set(valor_tarifa);
    }else{
        $('#modalTarifa').modal('show');
        return false;
    }

}

function validarTarifa(){
    var tarifaEspecial = tarifa_especial.getNumber();
    var tarifa_minima = $("#constantes").data('tarifaMinima');

    if (tarifaEspecial < tarifa_minima) {
        $('#modalTarifa').modal('show');
        return false;
    }
    return true;
}

function calcularTotal() {

    var descuento = descuentoOrden.getNumber();
    var envio = envioOrden.getNumber();
    var seguro = valtotalSeguro.getNumber();
    var flete = valtotal.getNumber();
    var impuestos = impuestosOrden.getNumber();
    var total = flete + impuestos + seguro + envio - descuento;

    if (total < 0) {
        total = 0;
    }
    valorTotalOrden.set(total);
    $("#valorTotalOrden").data('valorTotalOrden', total);
}

function setFleteCantidad() {
    var cantidad = $("#cantidad").val();
    var tarifa_4 = $("#constantes").data('tarifaCelular');
    var tarifa_5 = $("#constantes").data('tarifaComputador');
    var trm = $("#constantes").data('trm');
    if ($("#celular").is(":checked")) {
        flete.set((tarifa_4 * trm) * cantidad);
    }
    if ($("#pc").is(":checked")) {
        flete.set((tarifa_5 * trm) * cantidad);
    }
}


function showModalFacturaCostos(e)
{
    $("#modalFactura").modal('show');
    $("#costoId").val($(e).data("id"));
}

function calcularUSD(){
    var valor_usd = AutoNumeric.getNumber("#usd");
    var trm = $("#trm").val();
    AutoNumeric.set("#valor-costo",(valor_usd*trm));
}

function setModalUpdPrealerta(e)
{
    $("#tracking").val($(e).data('articuloTracking'));
    $("#valor").val($(e).data('articuloUsd'));
    if($(e).data('articuloFactura')!==""){
        $("#factura").prop('download', "factura de compra");
        $("#factura").attr('href',$(e).data('articuloFactura'));
    }else{
        $("#factura").removeAttr("download");
        $("#factura").attr('href',"#");
    }
    
    if($(e).data('articuloSeguro')==="si"){
        $("#seguro_pre").removeAttr("disabled");
        $("#seguro_pre").prop('checked', "factura de compra");
    }else{
        $("#seguro_pre").prop('disabled', true);
        $("#seguro_pre").prop('checked', false);
        
    }

    $("#articuloId").val($(e).data('articuloId'));
    validarSeguro()

}

function setModalDelPrealerta(e)
{
    $("#articuloIdEliminar").val($(e).data('articuloId'));
}