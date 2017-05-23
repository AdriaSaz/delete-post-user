<?php
/**
	Plugin Name:	Delete Post User
	Plugin URI:		http://bitman.es
	Description:	Delete all post from user selected
	Version:		0.1
	Author:			bitman
	License:		GPLv2 or Later 

**/


add_action( 'wp_print_scripts', 'enqueue_my_scripts' );
add_action( 'wp_enqueue_scripts', 'enqueue_my_styles' );
 
function enqueue_my_scripts(){
wp_enqueue_script( 'myScript', plugins_url('delete_post_user/js/script.js'), array( 'jquery' ));
}

function enqueue_my_styles(){
  wp_register_style( 'DPU-style', plugins_url('style.css', __FILE__) );
    wp_enqueue_style( 'DPU-style' );
}



function custom_styles() { 
 
  wp_deregister_style( 'bootstrap' );
 
  wp_register_style( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css', false, '3.3.6' );
 
  wp_enqueue_style( 'bootstrap' ); 

  wp_register_style( 'DPU-style', plugins_url('style.css', __FILE__) );
  
  wp_enqueue_style( 'DPU-style' );
 
}




function custom_scripts() {
 
        // Registramos JQUERY
 wp_deregister_script( 'jquery' );
 wp_register_script( 'jquery', 'http://code.jquery.com/jquery-2.1.1.min.js', false, '2.1.1', false );
 wp_enqueue_script( 'jquery' );
 
        //REGISTRAMOS BOOTSTRAP
 wp_deregister_script( 'bootstrap' );
 wp_register_script( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js', array( 'jquery' ), '3.3.6', false );
 wp_enqueue_script( 'bootstrap' );
 
}






//añadimos pagina en el panel de admin

function DPU_admin_menu(){


	add_menu_page(
			'Delete post user panel',		//titulo pag
			'Delete post user',				//titulo en el menu
			'administrator',				//rol accesible
			'delete-post-user-settings',		//ide de la pag
			'DPU_page_settings',			//funcio que pinta la pag
			'dashicons-admin-generic'		//icono menu
		);
}
add_action('admin_menu', 'DPU_admin_menu');

add_action( 'wp_enqueue_scripts', 'custom_styles' );

add_action( 'wp_enqueue_scripts', 'custom_scripts' );



if(isset($_REQUEST['delete_post'])){
global $wpdb;
$query=" delete wp_posts, wp_postmeta
  from wp_posts
 left join wp_postmeta
  on wp_posts.ID=wp_postmeta.post_id
  where wp_posts.`post_author` = ".$_REQUEST['delete_post'];
$wpdb->query($query);
	
}


function DPU_page_settings(){
?>

	<div class="wrap">
	<h1 class="DPU_tittle">Delete Post user</h1>
<form method="POST" ACTION="#">


	<ul>

	<div class="col-md-12">
		<table class="table">
		<thead>
			<tr>
				<th>Usuario</th>
				<th>Num Ofertas</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php
		$usuarios = get_users('orderby=nicename&role=author');
		foreach ($usuarios as $usuario) {
		//var_dump($usuario);
			$buton = (DPU_ads_check($usuario->ID)) ? add_button('onclick','delete_posts',$usuario->ID, 'Eliminar Anuncios') : "" ;
		    //echo '<li>' . $usuario->display_name ."->post: ". count_user_posts( $usuario->ID ). " Anuncios ".$buton ."</li>";
		//    echo '<li>' . $usuario->display_name ."->post: ". count_user_posts( $usuario->ID ). " Anuncios ".$buton ."</li>";
		    echo "<tr>";
			    echo "<td>$usuario->display_name</td>";
			    echo "<td>".count_user_posts( $usuario->ID )."</td>";
			    echo "<td>$buton</td>";
		    echo "</tr>";
		}
		?>
		</tbody>
		</table>
	</div>
	</ul>
</form>
	</div>

<?php


}

















///return true if have 1 or more ads
function DPU_ads_check($id){

	if(count_user_posts($id) >= 1){

		return true;
	} else {

		return false;
	}
}


function add_button($event, $func,$paramFunc, $text){

	//return "<button ".$event."='".$func."(".$paramFunc.")'>".$text."</button>";
	return "<button type='submit' name='delete_post' value='".$paramFunc."' class='DPU_del_post_buton'>".$text."</button>";

}


?>