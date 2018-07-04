$(document).ready(function() {

    // var dtColumns = (dataTablesColumns)? dataTablesColumns : '';

    // var columns = [];
    // $.each(dtColumns, function( key, value) {
    //   var data = {'data': value, 'orderable': false};
    //   columns.push(data);
    // });

    // console.log(JSON.stringify(dataTablesColumns));

    $('#' + endpoint).DataTable( {
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "ajax":{
            url :"../../ajax/loadtable", // json datasource
            error: function(){  // error handling
                $("." + endpoint + "-error").html("");
                $("#" + endpoint).append('<tbody class="employee-grid-error"><tr><th colspan="3">Nenhum registro encontrado</th></tr></tbody>');
                $("#" + endpoint + "_processing").css("display","none");

            }
        },
        "columns": [
            {"data": "id"},
            {"data": "name"},
            {"data": "create_date"}
        ],
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