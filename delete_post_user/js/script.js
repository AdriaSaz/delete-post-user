

function delete_posts(id_author){

	
        jQuery.ajax({
                data: {id:id_author},
                url:   'http://www.elsuperrastrillo.es/ofertas/wp-content/plugins/delete_post_user/ajax.php',
                type:  'post',
               /* beforeSend: function () {
                        $("#resultado").html("Procesando, espere por favor...");
                },*/
                success:  function(res) {
                //	console.log(res);
                      
                }
        });
}