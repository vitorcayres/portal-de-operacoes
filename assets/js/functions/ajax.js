/** 
* Função: Login do usuário
* Pagina: auth/login
*/
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