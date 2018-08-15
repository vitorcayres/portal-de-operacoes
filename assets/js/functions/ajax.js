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

/** 
* Função: Recupera o parceiro equivalente pelo id da oferta
* Pagina: canais
*/
$("#offer").change(function() {
    var id = $(this).find(':selected').attr('data-partner-id');

    $('#partner').empty();
    $.post(base_url + "/ajax/busca-parceiro", {id: id}, function( data ) {
          $('#partner').empty();
        $.each(data, function(i, obj) {
            $('#partner').append($('<option>').text(obj.name).attr('value', obj.id));
        });
    });
});

/** 
* Função: Adiciona a data de envio no calendario
* Pagina: canais
*/
$('#add').click( function() {
    var weekday = $("#weekday").val();
    var time = $("#time").val();

    if($("#weekday").val() != "" && $("#time").val() != "" && !$("#ipt_"+weekday+"_"+time.replace(':', '')).size()){
        var div = $("<div id='div_"+weekday+"_"+time.replace(':', '')+"'><span class='badge badge-primary'>"+ time +" <i class='fa fa-times' id='remove_"+weekday+"_"+time.replace(':', '')+"'></i></span><input type='hidden' id='ipt_"+weekday+"_"+time.replace(':', '')+"' value='"+ time +"' name='schedulingRules["+ weekday +"][]' /></div>");

        $("#td-"+weekday).append(div);   
        
        $("#remove_"+weekday+"_"+time.replace(':', '')+"").click(function(){
            $("#div_"+weekday+"_"+time.replace(':', '')+"").remove();
        });
    }
});

/** 
* Função: Remove a data de envio no calendario
* Pagina: canais
*/
$(document).ready(function(){
    $(".fa.fa-times").click(function(){
        $(this).parent().parent().remove();
    });
});