<?php
/**
 * Template Name: Single Product Template
 *
 */

get_header(); ?>

	<?php do_action( 'ocean_before_content_wrap' ); ?>

	<div id="content-wrap" class="container clr">

		<?php do_action( 'ocean_before_primary' ); ?>

            <div id="primary" class="content-area clr">

			<?php do_action( 'ocean_before_content' ); ?>

			<div id="content" class="site-content clr">

				<?php do_action( 'ocean_before_content_inner' ); ?>
                            
                            <section class="product-container">
                                
                                <div class="product-catalog">
                                    <div class="product-main-image">
                                        <div class="gallery-image">
                                            <?php
                                            $u = get_the_post_thumbnail_url( get_the_ID() , 'full' );
                                            
                                            ?>
                                            <div class="item-image" style="background-image: url(<?php echo $u ?>)"></div>
                                        </div>
                                    </div>
                                     <?php echo Tecnibo_Portfolio::get_carousel_product_images ( get_the_ID() ) ?>                
                                </div><!-- .product-catalog -->
                                
                                <div class="product-detail">
                                    <h1><?php the_title();?></h1>
                                    <?php the_content();?>
                                    <div class="divider"><hr class="flush"></div>
                                <?php

                                    if ( Tecnibo_Portfolio::has_meta( '_pdf_file' , get_the_ID() )
                                            || Tecnibo_Portfolio::has_meta( '_pdf_file_1' , get_the_ID() )
                                            || Tecnibo_Portfolio::has_meta( '_pdf_file_2' , get_the_ID() )){
                                            echo Tecnibo_Portfolio::get_pdf_link ( get_the_ID() ) ;
                                            echo '<div class="divider"><hr class="flush"></div>';
                                            }
                                ?>
                                 <?php
                                 
                                 if( Tecnibo_Portfolio::has_variations( get_the_ID() )){
                                     echo Tecnibo_Portfolio::get_variations( get_the_ID() ) ;
                                 }
                                 ?>
                                </div><!-- .product-detail -->
                                
                            </section><!--- .product-container -->
                            <?php do_action( 'ocean_social_share' ); ?>
                            <?php
                            //Related Projects 
                            if ( Tecnibo_Portfolio::has_related_objects( get_the_ID() ) ){ ?>
                               <?php echo Tecnibo_Portfolio::get_related_products_projects ( '_related_projects' , get_the_ID() , 'tecnibo_project' ); ?>
                           <?php } ?>
                            <?php do_action( 'ocean_before_content_inner' ); ?>

                            <?php do_action( 'ocean_after_content_inner' ); ?>
			</div><!-- #content -->

			<?php do_action( 'ocean_after_content' ); ?>

		</div><!-- #primary -->

		<?php do_action( 'ocean_after_primary' ); ?>

	</div><!-- #content-wrap -->

	<?php do_action( 'ocean_after_content_wrap' ); ?>

<?php get_footer();
