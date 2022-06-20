<?php get_header(); ?>



<main class="container my-3">

    <!-- Custom PostType ejemplo -->
    <?php if (have_posts()){
        while (have_posts()) {
            the_post(); ?>
    <h1 class="my-3"><?php the_title(); ?></h1>
    <div class="row">
        <div class="col-6">
            <?php the_post_thumbnail( "large");?>
        </div>
        <div class="col-6">
            <?php the_content();?>
        </div>
    </div>
    <?php
        }
    }?>

    <!-- Productos Relacionados -->
    <?php
    $ID_producto_actual = get_the_ID();
    $args = array(
      'post_type'       => 'producto',
      'posts_per_page'  => 6,
      'order'           => 'ASC',
      'orderby'         => 'title'
    );
    // En la siguiente variable se define el contenido
    // que vamos a solicitar a la base de datos, a través
    // del array de argumentos previamente definidos.
    // Ahora la variable $productos es un objeto con la configuración
    // necesaria para solicitar contenido.
    $productos = new WP_Query($args);
  ?>
    <!-- Ejecutar el loop con el objeto $productos -->
    <?php if($productos->have_posts()) { ?>
    <div class="row justify-content-center productos-relacionados">
        <div class="col-12">
            <h3 class="my-3 text-center">Productos relacionados</h3>
        </div>
        <?php while($productos->have_posts()) { ?>
        <?php $productos->the_post(); ?>
        <?php if(get_the_ID() != $ID_producto_actual) { ?>
        <div class="col-2 my-3 text-center">
            <?php the_post_thumbnail('thumbnail'); ?>
            <h4>
                <a href="<?php the_permalink(); ?>">
                    <?php the_title(); ?>
                </a>
            </h4>
        </div>
        <?php } ?>
        <?php } ?>
    </div>
    <?php } ?>
</main>

<?php get_footer(); ?>