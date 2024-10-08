<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$freelancer_layout = freeio_get_freelancer_layout_type();
$freelancer_layout = !empty($freelancer_layout) ? $freelancer_layout : 'v1';

?>
<section class="freelancer_single_layout freelancer-detail-version-<?php echo esc_attr($freelancer_layout); ?>">
	<section id="primary" class="content-area inner">
		<div id="main" class="site-main content" role="main">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post();
					global $post;
					if ( $post->post_status == 'expired' ) {
						echo WP_Freeio_Template_Loader::get_template_part( 'content-single-freelancer-expired' );
					} else {
						if ( method_exists('WP_Freeio_Freelancer', 'check_view_freelancer_detail') && !WP_Freeio_Freelancer::check_view_freelancer_detail() ) {
							?>
							<div class="restrict-wrapper container">
								<!-- list cv package -->
								<?php
									$restrict_detail = wp_freeio_get_option('freelancer_restrict_detail', 'all');
									switch ($restrict_detail) {
										case 'register_user':
											?>
											<h2 class="restrict-title"><?php esc_html_e( 'This page is restricted for registered users only.', 'freeio' ); ?></h2>
											<div class="restrict-content"><?php esc_html_e( 'Please login to view this page', 'freeio' ); ?></div>
											<?php
											break;
										case 'only_applicants':
											?>
											<h2 class="restrict-title"><?php esc_html_e( 'The page is restricted only for employers view his applicants.', 'freeio' ); ?></h2>
											<?php
											break;
										case 'register_employer':
											?>
											<h2 class="restrict-title"><?php esc_html_e( 'Please login as employer to view freelancer.', 'freeio' ); ?></h2>
											<?php
											break;
										default:
											$content = apply_filters('wp-freeio-restrict-freelancer-detail-information', '', $post);
											echo trim($content);
											break;
									}
								?>
							</div><!-- /.alert -->

							<?php
						} else {
						?>
							<div class="single-listing-wrapper freelancer" <?php freeio_freelancer_item_map_meta($post); ?>>
								<?php
									if ( $freelancer_layout !== 'v1' ) {
										echo WP_Freeio_Template_Loader::get_template_part( 'content-single-freelancer-'.$freelancer_layout );
									} else {
										echo WP_Freeio_Template_Loader::get_template_part( 'content-single-freelancer' );
									}
								?>
							</div>
						<?php } ?>
					<?php } ?>
				<?php endwhile; ?>

				<?php the_posts_pagination( array(
					'prev_text'          => esc_html__( 'Previous page', 'freeio' ),
					'next_text'          => esc_html__( 'Next page', 'freeio' ),
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'freeio' ) . ' </span>',
				) ); ?>
			<?php else : ?>
				<?php get_template_part( 'content', 'none' ); ?>
			<?php endif; ?>
		</div><!-- .site-main -->
	</section><!-- .content-area -->
</section>
<?php get_footer();
