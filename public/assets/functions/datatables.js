     $(document).ready(function() {
        var dataTable = $('#'+ page).DataTable( {
          "processing": true,
          "serverSide": true,
          "order": [ 0, 'asc' ],
          "ajax":{
            url : gdu_hostname + '/workplace',
            type: "post",
            error: function(){
              $(".employee-grid-error").html("");
              $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">Nenhum registro encontrado</th></tr></tbody>');
              $("#employee-grid_processing").css("display","none");
            }
          }
        } );
        
        $('#employee-grid tbody').on('click', '#del', function () {
          var result = confirm("Você realmente deseja excluir esse registro?");
            if (result) {
              return true;
          }else{
            return false;
          }
        }
      );
    });