/** 
* Função: Validação do campo permissão
* Pagina: /alterar-senha
*/
$(".valida-permissao").keyup(function() {
	$(this).val($(this).val().toLowerCase());   
	$(this).val($(this).val().replace(" ", ""));
	$(this).val($(this).val().replace(/[^a-z -]+/g,''));
});

/** 
* Função: Alterar senha do usuário
* Pagina: /alterar-senha
*/
jQuery.validator.setDefaults({
    debug: true,
    success: "valid",
});

$("#form_alterar_senha").validate({
    onkeyup: function(element) {
        this.element(element);
    },
    onfocusout: function(element) {
        this.element(element);
    },
    rules: {
        password: {
            required: true,
            minlength: 8,
            maxlength: 12
        },
        confirm_password: {
            required: true,
            equalTo: "#password"
        }
    },
    messages: {
        password: {
            required: "Informe sua nova senha!",
            minlength: "Mínimo de caracteres permitidos: 8",
            maxlength: "Maxímo de caracteres permitidos: 12"
        },
        confirm_password: {
        	required: "Confirme sua nova senha!",
            equalTo: "As senhas não conferem, digite novamente!"
        }
    },
	submitHandler: function(form) {
		form.submit();
	} 
});

/** 
* Função: Select com busca
* Pagina: todas as paginas
*/
$(".select2").select2({
    allowClear: false
});

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