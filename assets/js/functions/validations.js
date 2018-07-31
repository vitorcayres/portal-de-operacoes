$("#login").click(function() {
    var username = $('#username').val();
    var password = $('#password').val();

    if(username && password){
        var data = {username:username, password: password}; 
        $.ajax({
            type: "POST",
            url: "../auth/login",
            dataType: 'json',
            data: data,            
            beforeSend: function(){
                $('#login').empty().append('Entrando...');
                $('.alert').hide();
            },
            success: function (data){
                $(location).attr('href','../dashboard');
            },
            error: function(data) {
                var obj = data.responseJSON;
                $('#login').empty().append('Entrar');
                $('.alert').show();
                $('.alert').empty().append(obj.message);
            }              
        });
    }else{
        $('.alert').show();
        $('.alert').empty().append('Preencha todos os campos acima!');
    }
});

/* Validação do campo permissão */
$(".valida-permissao").keyup(function() {
	$(this).val($(this).val().toLowerCase());   
	$(this).val($(this).val().replace(" ", ""));
	$(this).val($(this).val().replace(/[^a-z -]+/g,''));
});

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