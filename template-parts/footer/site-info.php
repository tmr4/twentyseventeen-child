<?php
/**
 * Displays footer site info
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since Twenty Seventeen 1.0
 * @version 1.0
 */

 /*
 Child theme modifications:
	- removed WordPress branding at line 30

	<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'twentyseventeen' ) ); ?>" class="imprint">
		<?php
			printf( __( 'Proudly powered by %s', 'twentyseventeen' ), 'WordPress' );
			?>
	</a>
		
*/

?>
<div class="site-info">
	<?php
	if ( function_exists( 'the_privacy_policy_link' ) ) {
		the_privacy_policy_link( '', '<span role="separator" aria-hidden="true"></span>' );
	}

	$tc_page = get_page_by_title( 'Terms and Conditions' );
	$tc_url = get_permalink($tc_page->ID);
	$cu_page = get_page_by_title( 'Contact' );
	$cu_url = get_permalink($cu_page->ID);

	?>
	<a href=" <?php echo esc_url( $tc_url ); ?> ">Terms and Conditions<span role="separator" aria-hidden="true"></span></a>
	<a href=" <?php echo esc_url( $cu_url ); ?> ">Contact Us</a>
</div><!-- .site-info -->
<div class="copyright">
	<a>Copyright &copy; 2020 TRobertson</a>
</div><!-- .copyright -->
