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