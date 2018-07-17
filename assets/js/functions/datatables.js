$(document).ready(function() {
    var table = $('#' + pagina).DataTable( {
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "searching": false,
        "ajax":{
            url :"loadtable",
            error: function(){
                $("." + pagina + "-error").html("");
                $("#" + pagina).append('<tbody class="employee-grid-error"><tr><th colspan="3">Nenhum registro encontrado</th></tr></tbody>');
                $("#" + pagina + "_processing").css("display","none");
            }
        },
        "language": {
          "sEmptyTable": "Nenhum registro encontrado",
          "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
          "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
          "sInfoFiltered": "(Filtrados de _MAX_ registros)",
          "sInfoPostFix": "",
          "sInfoThousands": ".",
          "sLengthMenu": "_MENU_ resultados por página",
          "sLoadingRecords": "Carregando...",
          "sProcessing": "Carregando...",
          "sZeroRecords": "Nenhum registro encontrado",
          "sSearch": "Pesquisar",
          "oPaginate": {
              "sNext": "Próximo",
              "sPrevious": "Anterior",
              "sFirst": "Primeiro",
              "sLast": "Último"
          },
          "oAria": {
              "sSortAscending": ": Ordenar colunas de forma ascendente",
              "sSortDescending": ": Ordenar colunas de forma descendente"
          }
      }        
    } );

    // Função para excluir um registro
    $('#'+pagina+' tbody').on('click', '#delete', function () {
        var data = table.row( $(this).parents('tr') ).data();

        swal({
            title: "Você tem certeza?",
            text: "Você não poderá recuperar este registro!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sim, excluir!",
            cancelButtonText: "Cancelar",
            closeOnConfirm: false,
            allowEscapeKey: false
          }, function () {

            $.ajax({
              type: "POST",
              url: "remover/"+data[0],
              data: {id: data[0]},            
              success: function (data){

                var data = JSON.parse(data);               
                console.log('1' + data.status);

                if(data.status == 'true'){
                  table.ajax.reload();
                  swal("Deletado!", "Seu registro foi excluído.", "success");
                }else{
                  swal("Erro!", ""+ data.message +"", "error");                
                }
              },
              error: function(data) {
                return false;
              }              
            });
        });
    });
    
});