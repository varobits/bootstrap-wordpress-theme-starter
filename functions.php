<?php

/* Funcionalidades básicas del theme */

function init_template(){
    add_theme_support( "post-thumbnails");
    add_theme_support( "title-tag");

    register_nav_menus(
        array(
            'top_menu' => 'Menú Principal'
        )
    );

};

/* Registrando estilos y dependecias */

add_action( "after_setup_theme", "init_template");

function assets(){

    wp_register_style( "bootstrap", "https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css", "", "4.4.1", "all" );

    wp_register_style("montserrat", "https://fonts.googleapis.com/css?family=Montserrat&display=swap", "", "1.0","all");

    wp_enqueue_style( "estilos", get_stylesheet_uri(), array("bootstrap","montserrat"), "1.0", "all" );

    wp_register_script('popper','https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js','','1.16.0', true);

    wp_enqueue_script('boostraps', 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js', array('jquery','popper'),'4.4.1', true);

    wp_enqueue_script('custom', get_template_directory_uri().'/assets/js/custom.js', '', '1.0', true);
    
    wp_localize_script('custom', 'pg', array(
        'ajaxurl' => admin_url('admin-ajax.php')
    ));
};

add_action( "wp_enqueue_scripts", "assets");

/* Añadiendo sidebar */

function sidebar(){
    register_sidebar(
        array(
            'name' => 'Pie de pagina',
            'id' => 'footer',
            'description' => 'Zona de Widgets para pie de pagina',
            'before_title' => '<p>',
            'after_title' => '</p>',
            'before_widget' => '<div id="%1$s" class= "%2$s">',
            'after_widget'  => '</div>',
        )    
        );
    
}

add_action('widgets_init', 'sidebar');

/* Añadiendo custom post type básico */

function productos_type(){
    $labels = array(
        'name' => 'Productos',
        'singular_name' => 'Producto',
        'menu_name' => 'Productos',
    );

    $args = array(
        'label'  => 'Productos', 
        'description' => 'Productos básicos',
        'labels'       => $labels,
        'supports'   => array('title','editor','thumbnail', 'revisions'),
        'public'    => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'menu_icon'     => 'dashicons-cart',
        'can_export' => true,
        'publicly_queryable' => true,
        'rewrite'       => true,
        'show_in_rest' => true

    );    
    register_post_type('producto', $args);
}

add_action('init', 'productos_type');

/* Añadiendo custom taxonomy */


add_action( "init","pgRegisterTax");

function pgRegisterTax(){
        $args = array(
            "hierarchical" => true,
            "labels" => array (
                "name" => "Categorías de Productos",
                "singular_name" => "Categoría de Productos" 
                ),
            "show_in_nav_menu" => true,
            "show_admin_column" => true,
            "rewrite" => array("slug" => "categoria-productos")
            );

        register_taxonomy( "categoria-productos", array("producto"), $args);
}

add_action("wp_ajax_pgFiltroProductos","pgFiltroProductos" );
add_action("wp_ajax_nopriv_pgFiltroProductos","pgFiltroProductos" );

function filtroProductos(){

    $args = array(
        'post_type' => 'producto',
        'posts_per_page' => -1,
        'order'     => 'ASC',
        'orderby' => 'title',
        'tax_query' => array(
            array(
                'taxonomy' => 'categorias-productos',
                'field' => 'slug',
                'terms' => $_POST['categoria']
            )
        )
    );
    $productos = new WP_Query($args);

    $return = array();
    if ($productos->have_posts()) {
        while($productos->have_posts()){
            $productos->the_post();
            $return[] = array(
                'imagen' => get_the_post_thumbnail(get_the_ID(), 'large'),
                'link' => get_permalink(),
                'titulo' => get_the_title()
            );
        }
    }

    wp_send_json($return);
};

wp_add_id3_tag_data( $metadata:array, $data:array );