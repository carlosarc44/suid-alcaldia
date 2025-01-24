var base_url = $('meta[name="base_url"]').attr('content') 
var loader = '<img src="'+base_url+'/img/loading.gif">'

function misQuejas(vigencia)
{
    var ruta = base_url +'/quejas/misQuejas'
  	
  	$.ajax({                
        data:  {vigencia},
        url:   ruta,
        type:  'post',
        beforeSend: function(){
            $("#ajax-quejas").html("<p style='width:100%; margin-top:20px; text-align:center;'>"+loader+"</p>")    		   
        },
        success:  function (responseText) {
            $("#tituloFecha").html("recibidas durante la vigencia "+vigencia);
            $("#ajax-quejas").hide().html(responseText).fadeIn(600)
            //Tabla quejas
            
            $('#tablaQuejas').DataTable({
                iDisplayLength: 100,
                'order': [[0, "desc"]],
                fixedHeader: {
                  header: true,
                  headerOffset: 49
                }
            });
        },
        error: function (responseText) {
            switch (responseText.status) 
            {
                case 500: 
                    console.error('Error '+responseText.status+' '+responseText); 
                    break;
                case 401:
                    alertify.confirm('<b>Sesión desconectada</b>', 'Será redirigido al login para que ingrese nuevamente', function() { 
                            var rutaRedirect = base_url +'/'+ 'login';
                            window.location.href = rutaRedirect;  
                        }, 
                        function() {                 				
                            return false;
                        }
                    );   
                break;
            }
        }
   	});
}



function finalizarTarea(idTarea)
{
	if ($('#chk-'+idTarea).is(':checked'))
	{
    	var valor = 1;
    }
    else
    {
    	var valor = 0;
    }

    var ruta = base_url +'/agenda/finalizarTarea'

    var parametros = {
        "idTarea" : idTarea,
        "valor" : valor
    };

    $.ajax({
        data:  parametros,
        url:   ruta,
        type:  'post',
        success:  function (responseText)
        {
          	playAudio("alert");
          	alertify.success("Guardado en la base de datos");
          	$("#resultadoPorcentaje").html(responseText);
        },
        error: function(responseText)
        {
          alert("Error.  Contacte al administrador (Cod.Error.280)");
        }
    });
}

