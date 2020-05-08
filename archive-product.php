<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

?>
<header class="woocommerce-products-header">
	<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
		<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
	<?php endif; ?>

	<?php
	/**
	 * Hook: woocommerce_archive_description.
	 *
	 * @hooked woocommerce_taxonomy_archive_description - 10
	 * @hooked woocommerce_product_archive_description - 10
	 */
	do_action( 'woocommerce_archive_description' );
	?>
</header>
<?php
if ( woocommerce_product_loop() ) {
   
                    // Only run on shop archive pages, not single products or other pages
                    if ( is_shop() || is_product_category() || is_product_tag() ) {
                        // Products per page
                        $per_page = 24;
                        if ( get_query_var( 'taxonomy' ) ) { // If on a product taxonomy archive (category or tag)
                            $args = array(
                                'post_type' => 'product',
                                'posts_per_page' => $per_page,
                                'paged' => get_query_var( 'paged' ),
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => get_query_var( 'taxonomy' ),
                                        'field'    => 'slug',
                                        'terms'    => get_query_var( 'term' ),
                                    ),
                                ),
                            );
                        } else { // On main shop page
                            $args = array(
                                'post_type' => 'product',
                                'orderby' => 'date',
                                'order' => 'DESC',
                                'posts_per_page' => $per_page,
                                'paged' => get_query_var( 'paged' ),
                            );
                        }
                        // Set the query
                        $loop = new WP_Query( $args );
                        // Standard loop
                        if ( $loop->have_posts() ) :
                            
                             foreach($loop->posts as $p):
                                	$pid = $p->ID;
                                	$product = new WC_Product( $pid );
                                	
                                	 $terms = get_the_terms( $p->ID, 'product_cat' );
                                    if ( $terms && ! is_wp_error( $terms ) ) :
                                    //only displayed if the product has at least one category
                                        $cat_links = array();
                                        foreach ( $terms as $term ) {
                                            $cat_links[] = $term->name;
                                        }
                                        $on_cat = join( " ", $cat_links );
                                        ?>
                                        <div class="label-group">
                                            <?php echo 'Categories: ' . $on_cat; ?>
                                        </div>
                                    <?php endif;
                                    echo '<div class="cat-single-product-list">';
                                    
                                    echo '<h3 class="cat-single-pro-title"><a href="'.get_permalink($pid).'">'.$p->post_title.'</a></h3>';
                                    
                                // 	echo '<span class="price">'.$product->get_price_html().'</span>'; 
                                	echo '<p class="cat-single-pro-desc"> '.$p->post_excerpt.'...  <a style="color:#0674EC;" href="'.get_permalink($pid).'">read more >></a></p>';
                                	
                                	echo '<span class="cat-single-cat"> <i aria-hidden="true" class="fas fa-list-ul cat-single-icn"></i><a style="color:#7347c1;" href="'.get_category_link( $terms->term_id).'"> ' . $on_cat . '</a></span>';
                                	echo '<span class="cat-single-date"><i aria-hidden="true" class="far fa-calendar-alt cat-single-icn"></i> '.$p->post_date_gmt.'</span>';
                                	echo '<span class="cat-single-glbal"><i aria-hidden="true" class="fas fa-globe-americas cat-single-icn"></i> Global</span>';
                                	
                                // 	echo '<br/>'.the_field("pages");
                                	
                                
                                	echo '</div>';
                            
                            endforeach; 
                                            
                            wp_reset_postdata();
                        endif;
                    } else { // If not on archive page (cart, checkout, etc), do normal operations
                        woocommerce_content();
                    }
            

    
} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
}

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );
