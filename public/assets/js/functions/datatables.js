$(document).ready(function() {

    var dtCL = (dataTablesColumns)? dataTablesColumns : '';
    var arrColumns = dtCL.split(",");

    var dtColumns = [];
    $.each(arrColumns, function( k, v) {
      var data = {'data': v.replace(" ", ""), 'orderable': false};
      dtColumns.push(data);
    });

    dtColumns.push({'data': null, 'orderable': false, 'defaultContent': '<a id="modify" title="Editar"><i class="fa fa-edit"></i></a>&nbsp;|&nbsp;&nbsp;<a id="delete" title="Excluir"><i class="fa fa-remove"></i></a>'});

    // Datatables
    var table = $('#' + pagina).DataTable( {
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "searching": false,
        "ajax":{
            url :"../../ajax/loadtable?hostname="+hostname+"&token="+token+"&endpoint="+endpoint+"",
            error: function(){
                $("." + pagina + "-error").html("");
                $("#" + pagina).append('<tbody class="employee-grid-error"><tr><th colspan="3">Nenhum registro encontrado</th></tr></tbody>');
                $("#" + pagina + "_processing").css("display","none");

            }
        },
        "columns": dtColumns,
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

    // Função para mostrar o detalhe de um registro
    $('#'+pagina+' tbody').on('click', '#details', function () {
        var data = table.row( $(this).parents('tr') ).data();
        window.location = "detalhes/"+data.id;        
    });

    // Função para editar um registro
    $('#'+pagina+' tbody').on('click', '#modify', function () {
        var data = table.row( $(this).parents('tr') ).data();
        window.location = "editar/"+ data.id;
    });

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
              url: "remover/"+data.id,
              data: {id: data.id},            
              success: function (data){
                table.ajax.reload();
                swal("Deletado!", "Seu registro foi excluído.", "success");
              },
              error: function(data) {
                return false;
              }              
            });
        });
    });
    
});