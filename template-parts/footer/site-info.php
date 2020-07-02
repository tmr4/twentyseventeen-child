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
		
	- added 'Terms and Contitions' and 'Contact' with links after privacy policy
		- link will be added, along with separator if appropriate, if page exists and is published
		- these page names are currently hard coded *** consider changing to options ***
	- added copyright notice to right of footer in form
		Copyright © <base year>-<current-year> <site_name>
		- if base year is equal to current year then only base year is shown
		- base year hard coded to 2020 *** consider making an option ***
*/

?>
<div class="site-info">
	<?php
	$tc_page_published = false;
	$cu_page_published = false;

	$tc_page = get_page_by_title( 'Terms and Conditions' );
	if( !is_null( $tc_page ) && $tc_page->post_status == 'publish' ) { 
		$tc_page_published = true;
	}

	$cu_page = get_page_by_title( 'Contact' );
	if( !is_null( $cu_page ) && $cu_page->post_status == 'publish' ) { 
		$cu_page_published = true;
	}

	if ( function_exists( 'the_privacy_policy_link' ) ) {
		if( $tc_page_published || $cu_page_published ) {
			the_privacy_policy_link( '', '<span role="separator" aria-hidden="true"></span>' );
		}
		else {
			the_privacy_policy_link( '', '' );
		}
	}

	if( $tc_page_published ) { 
		$tc_url = get_permalink($tc_page->ID); 
		if( $cu_page_published ) { ?>
			<a href=" <?php echo esc_url( $tc_url ); ?> ">Terms and Conditions<span role="separator" aria-hidden="true"></span></a>
<?php   }
		else { ?>
			<a href=" <?php echo esc_url( $tc_url ); ?> ">Terms and Conditions</a>
<?php   }
	}

	if( $cu_page_published ) { 
		$cu_url = get_permalink($cu_page->ID); ?>
		<a href=" <?php echo esc_url( $cu_url ); ?> ">Contact Us</a>
<?php
	}

	$cpyear = "2020";
	if( date("Y") > $cpyear) {
		$cpyear .= "-" . date("Y");
	}
	?>

</div><!-- .site-info -->
<div class="copyright">
	<a>Copyright &copy; <?php echo $cpyear . " " . get_bloginfo(); ?></a>
</div><!-- .copyright -->
