<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 */
?>

	</main><!-- #content -->

	<?php
	pgsf_the_byu_footer();
	wp_footer();
	?>

	<!-- An unfortunate fix for now to keep the wp admin bar in place on mobile -->
	<style>
	@media screen and (max-width: 600px) {
	  #wpadminbar {
	    position: fixed;
	  }
	}
	</style>

</body>
</html>
