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
	?>
	<a href="https://test.wordpress/terms-and-conditions">Terms and Conditions</a>
</div><!-- .site-info -->
<div class="copyright">
	<a>Copyright &copy; 2020 TRobertson</a>
</div><!-- .copyright -->
