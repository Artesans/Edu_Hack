<?php
/**
 * The template for displaying all single projects
 * Created by PhpStorm.
 * User: mireiachaler
 * Date: 22/08/2017
 * Time: 13:07
 */

get_header(); ?>

    <div class="wrap">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">

                <?php
                /* Start the Loop */
                while ( have_posts() ) : the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <?php
                        if ( is_sticky() && is_home() ) :
                            echo twentyseventeen_get_svg( array( 'icon' => 'thumb-tack' ) );
                        endif;
                        ?>
                        <header class="entry-header">
                            <?php
                            if ( is_single() ) {
                                the_title( '<h1 class="entry-title">', '</h1>' );
                            } elseif ( is_front_page() && is_home() ) {
                                the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
                            } else {
                                the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
                            }
                            ?>
                        </header><!-- .entry-header -->

                        <?php if ( '' !== get_the_post_thumbnail() && ! is_single() ) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail(); ?>
                                </a>
                            </div><!-- .post-thumbnail -->
                        <?php endif; ?>

                        <div class="entry-content">
                            <?php
                            /* translators: %s: Name of current post */
                            the_content( );
                            ?>
                        </div><!-- .entry-content -->

                    </article><!-- #post-## -->

                    <?php

                    // If comments are open or we have at least one comment, load up the comment template.
                    if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif;

                    the_post_navigation( array(
                        'prev_text' => '<span class="screen-reader-text">' . __( 'Previous Post' ) . '</span><span aria-hidden="true" class="nav-subtitle">' . __( 'Previous' ) . '</span> <span class="nav-title"><span class="nav-title-icon-wrapper">&lt;&lt;&nbsp;</span>%title</span>',
                        'next_text' => '<span class="screen-reader-text">' . __( 'Next Post' ) . '</span><span aria-hidden="true" class="nav-subtitle">' . __( 'Next' ) . '</span> <span class="nav-title">%title<span class="nav-title-icon-wrapper">&gt;&gt;&nbsp;</span></span>',
                    ) );

                endwhile; // End of the loop.
                ?>

            </main><!-- #main -->
        </div><!-- #primary -->
        <?php get_sidebar(); ?>
    </div><!-- .wrap -->

<?php get_footer();
