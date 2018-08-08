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