<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 */
?>

	</main><!-- .page-content -->

	<?php
	pgsf_the_byu_footer();
	pgsf_the_analytics_scripts();
	pgsf_the_search_scripts();
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
