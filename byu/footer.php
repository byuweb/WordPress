<footer>
	<div id="footer-links">
		<div class="wrapper">
			
			
			<?php if ( is_active_sidebar( 'footer-sidebar-1' ) ) : ?>
			<div class="col alpha">
				<?php dynamic_sidebar( 'footer-sidebar-1' ); ?>
			</div>
			<?php endif; ?>	
			
				
				
			<?php if ( is_active_sidebar( 'footer-sidebar-2' ) ) : ?>
			<div class="col">
				<?php dynamic_sidebar( 'footer-sidebar-2' ); ?>
			</div>
			<?php endif; ?>	
			
			<?php if ( is_active_sidebar( 'footer-sidebar-3' ) ) : ?>
			<div class="col">	
				<?php dynamic_sidebar( 'footer-sidebar-3' ); ?>
			</div>
			<?php endif; ?>
			
			<?php if ( is_active_sidebar( 'footer-sidebar-4' ) ) : ?>
			<div class="col">	
				<?php dynamic_sidebar( 'footer-sidebar-4' ); ?>
			</div>
			<?php endif; ?>
			
			<?php if ( is_active_sidebar( 'footer-sidebar-5' ) ) : ?>
			<div class="col omega">	
				<?php dynamic_sidebar( 'footer-sidebar-5' ); ?>
			</div>
			<?php endif; ?>

			<?php wp_footer(); /* this is used by many Wordpress features and plugins to work properly */ ?>
		</div>
	</div>
	<div id="footer-bottom">
		<p><a href="http://home.byu.edu/home/copyright">Copyright&#169; <?php echo date("Y"); ?>, All Rights Reserved</a></p>
	</div>
</footer>
</body></html>