/** 
* Função: Carrega a listagem dos registros
* Pagina: *todas as paginas
*/
$(document).ready(function() {
  if(pagina){
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

    // Função para alterar senha do usuario
    $('#'+pagina+' tbody').on('click', '#alterarsenha', function () {
        var data = table.row( $(this).parents('tr') ).data();
        window.location = 'alterar-senha/' + data[0];
    });

    // Função para editar um registro
    $('#'+pagina+' tbody').on('click', '#editar', function () {
        var data = table.row( $(this).parents('tr') ).data();
        window.location = 'editar/' + data[0];
    });  

    // Função para excluir um registro
    $('#'+pagina+' tbody').on('click', '#remover', function () {
        var data = table.row( $(this).parents('tr') ).data();
        var id   = data[0];

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
              url: "remover/"+id,
              data: {id: id},            
              success: function (data){
                table.ajax.reload();
                swal("Deletado!", "Seu registro foi excluído.", "success");
              },
              error: function(data) {
                var rows = data.responseJSON;
                swal("Erro!", ""+ rows.message +"", "error");
              }              
            });
        });
    });
  } 
});