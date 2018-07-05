$(document).ready(function() {

    var dtCL = (dataTablesColumns)? dataTablesColumns : '';
    var arrColumns = dtCL.split(",");

    var dtColumns = [];
    $.each(arrColumns, function( k, v) {
      var data = {'data': v.replace(" ", ""), 'orderable': false};
      dtColumns.push(data);
    });

    $('#' + pagina).DataTable( {
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "ajax":{
            url :"../../ajax/loadtable?hostname="+hostname+"&token="+token+"&endpoint="+endpoint+"", // json datasource
            error: function(){  // error handling
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
} );