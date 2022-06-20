<?php get_header(); ?>

<!-- Home -->

<main class="container">
    <?php if (have_posts()){
        while (have_posts()) {
            the_post(); ?>

    <h1 class="my-3"> Home: <?php the_title(); ?></h1>

    <?php the_content(); ?>


    <?php }
    }?>

    <div class="lista-productos">
        <h2 class="text-center">Productos</h2>
        <div class="row">
            <div class="col-12 my-5">
                <select class="form-control" name="categoria-productos" id="categorias">
                    <option value="">Todas las categorías</option>
                    <?php $terms = get_terms('categoria-productos', ['hide_empty' => true]) ?>
                    <?php foreach ($terms as $term): ?>
                    <option value="<?php echo $term->slug  ?>">
                        <?php echo $term->name ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row">
            <?php

            $args = array (
                'post_type' => 'producto',
                'post_per_page' => -1, 
                'order'         => 'ASC',
            'orderby'       => 'title'
            );

            $productos = new WP_Query($args);

            if($productos->have_posts()){
                while($productos->have_posts()){
                    $productos->the_post();
                    ?>

            <div class="col-4">
                <figure>
                    <?php the_post_thumbnail('large'); ?>
                </figure>
                <h4 class='my-3 text-center'>
                    <a href="<?php the_permalink(); ?>">
                        <?php the_title();?>
                    </a>
                </h4>
            </div>

            <?php }
            }

            ?>

        </div>
    </div>
</main>

<?php get_footer(); ?>