<?php
/**
 * Template Name: Single Project Template
 *
 */

get_header(); ?>

	<?php do_action( 'ocean_before_content_wrap' ); ?>

	<div id="content-wrap" class="container clr">

		<?php do_action( 'ocean_before_primary' ); ?>

            <div id="primary" class="content-area clr">

			<?php do_action( 'ocean_before_content' ); ?>

			<div id="content" class="site-content clr">
                            <h2 class="project-title"><?php the_title();?></h2>
                            <section class="tecnibo-row">
                            <?php the_post_thumbnail('single-main-thumb') ?>
                            </section>
                            <section class="tecnibo-row">
                                <div class="project-overview">
                                    <h3><?php _e( 'Overview', 'tecnibo' )?></h3>
                                    <?php the_excerpt()?>
                                </div>
                                <div class="project-meta">
                                    <?php
                                        if ( Tecnibo_Portfolio::has_meta( '_project_scope' , get_the_ID() ) )
                                            echo Tecnibo_Portfolio::get_product_meta ( 'Scope' , '_project_scope' , get_the_ID() ) ;

                                        if ( Tecnibo_Portfolio::has_meta( '_project_client' , get_the_ID() ) )
                                            echo Tecnibo_Portfolio::get_product_meta ( 'Client' , '_project_client' , get_the_ID() );
                                        
                                        if ( Tecnibo_Portfolio::has_meta( '_project_product' , get_the_ID() ) )
                                            echo Tecnibo_Portfolio::get_product_meta ( 'Product' , '_project_product' , get_the_ID() ) ;                                        
                                        
                                        if ( Tecnibo_Portfolio::has_meta( '_project_photography' , get_the_ID() ) )
                                            echo Tecnibo_Portfolio::get_product_meta ( 'Photography' , '_project_photography' , get_the_ID() ) ;
                                    ?>
                                </div>
                            </section>
                            <div class="divider"><hr class="flush"></div>
                
                    <?php 
                        $featured_images = Tecnibo_Portfolio::get_featured_images( get_the_ID() );
                        if(count($featured_images) > 0 ){ 
                    ?>
                            <section class="project-photos tecnibo-row">
                                <?php 
                                foreach ($featured_images as $key => $value) { 
                                ?>
                                <div class="projetc-item-image"><img src="<?php echo $value['full']?>" /></div>
                                <?php  
                                } 
                                ?>
                            </section>
                            <div class="divider"><hr class="flush"></div>
                    <?php } ?>
                            
                            <section class="tecnibo-row">
                                <?php the_content() ?>
                            </section>
                            <div class="divider"><hr class="flush"></div>
                            <?php if ( Tecnibo_Portfolio::has_meta( '_project_video' , get_the_ID() ) ){ ?>
                            <section class="tecnibo-row">
                                <?php  echo Tecnibo_Portfolio::get_product_meta ( '' , '_project_video' , get_the_ID() , true ); ?>
                            </section> 
                            
                               <?php } ?>
                            <?php do_action( 'ocean_social_share' ); ?>
                            <?php
                            // Related Products
                            if ( Tecnibo_Portfolio::has_related_objects( get_the_ID() ) ){ ?>
                            <section class="tecnibo-row">
                               <?php echo Tecnibo_Portfolio::get_related_products_projects ( '_related_products' , get_the_ID() , 'tecnibo_product' ); ?>
                            </section>
                            <div class="divider"><hr class="flush"></div>
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



