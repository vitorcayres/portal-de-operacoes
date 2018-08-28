/** 
* Função: Poupup de publicacao das fraseologias
* Pagina: fraseologias
*/
$('.poupup').click(function () {
    swal({
        title: "Você tem certeza?",
        text: "Esta ação irá publicar todas as fraseologias cadastradas!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Publicar!",
        cancelButtonText: "Cancelar",
        closeOnConfirm: false,
        allowEscapeKey: false
    }, function () {
        $.ajax({
            type: "GET",
            url: "publicar",         
            success: function (data){
                swal("Sucesso!", "Fraseologias publicadas com sucesso!", "success");
            },
            error: function(data) {
                var rows = data.responseJSON;
                swal("Erro!", ""+ rows.message +"", "error");
            }              
        });
    });
});

/** 
* Função: Mascara de horas
* Pagina: canais
*/
var mask = function (val) {
    val = val.split(":");
    return (parseInt(val[0]) > 19)? "HZ:M0" : "H0:M0";
}

pattern = {
    onKeyPress: function(val, e, field, options) {
        field.mask(mask.apply({}, arguments), options);
    },
    translation: {
        'H': { pattern: /[0-2]/, optional: false },
        'Z': { pattern: /[0-3]/, optional: false },
        'M': { pattern: /[0-5]/, optional: false}
    }
};

$("#time").mask(mask, pattern);

/** 
* Função: Validar somente numero no campo
* Pagina: todas as paginas
*/
function somenteNumeros(num) {
    var er = /[^0-9]/;
    er.lastIndex = 0;
    var campo = num;
    if (er.test(campo.value)) {
      campo.value = "";
    }
}

$(document).on("input", "#message", function() {
    var limite = 160;
    var informativo = "caracteres restantes";
    var caracteresDigitados = $(this).val().length;
    var caracteresRestantes = limite - caracteresDigitados;

    if (caracteresRestantes <= 0) {
        var comentario = $("textarea[name=message]").val();
        $("textarea[name=message]").val(comentario.substr(0, limite));
        $(".caracteres").text("0 " + informativo);
    } else {
        $(".caracteres").text(caracteresRestantes + " " + informativo);
    }
});

$("#message").keyup(function() {
    this.value = this.value.replace(/[~´`^áéíóúàèìòùâêîôûãõç\\]+/g,'');    
    $('.preview-textarea-fraseologies').empty().val($(this).val());
});


// Criação da listagem de fraseologias
$("#preview").sortable({
    connectWith: ".connectList",
    update: function( event, ui ) {
        var preview = $( "#preview" ).sortable("toArray");
    }
}).disableSelection();


// Exibição das fraseologias 
var x = 1;
var recuperaValor = $("#countMessage").val();
var x = (recuperaValor)? parseInt(recuperaValor) + parseInt(1) : '1';
var click = [];

$('#adiciona-fraseologia').click( function() {
    var msg = $("#message").val();

    if(msg != ''){

        click = 1;

        $("#preview").append('<li class="success-element" id="message_'+ x +'"><textarea class="form-control" id="message" maxlength="160" name="messages['+x+']" >'+msg+'</textarea><div class="agile-detail"><a type="button" id="r'+x+'" data-delete="'+x+'" class="pull-right btn btn-xs btn-white">Remover</a>&nbsp;</div></li>');

        $("#r"+x+"").click(function(){
            $("#message_"+ $(this).attr('data-delete') +"").remove();
        });

        x++;             
    }
});

function removeMsg(id){
    $("#message_"+ id +"").remove();
}

// Informa a descrição dos tipos
$("#type").change(function() {
    var selected = $(this).find(':selected').attr('data-description');

    if(!selected){
        $("#description").hide();
    }

    $("#description").show();
    $("#description").html(selected);
    $("#briefDescription").val(selected);
});

// Informa o uuid da campanha
$("#campaignName").change(function() {
   var selected = $(this).find(':selected').attr('data-campaignuuid');
   $("#campaignUuid").val(selected);
});