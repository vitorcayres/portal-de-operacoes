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

/** 
* Função: Recupera o canal equivalente pelo id da oferta
* Pagina: noticias
*/
$("#offerId").change(function() {
    var id = $(this).val();

    $('#channelId').empty();
    $('#channelId').append($('<option>').text("[CARREGANDO...]"));

    $.post(base_url + "/ajax/busca-canal", {"id": id}, function( data ) {
          $('#channelId').empty();
          $('#channelId').append($('<option>').text("[FILTRAR PELO CANAL]").attr('value', 0));
        $.each(data, function(i, obj) {
            $('#channelId').append($('<option>').text(obj.name).attr('value', obj.id));
        });
    });
});