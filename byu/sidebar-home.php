<div class="sidebar omega">
	<?php if ( ! dynamic_sidebar( 'Home Sidebar' )) : ?>
	<h3>Archives</h3>
	<?php wp_get_archives( 'type=monthly' ); ?>

	<?php endif; ?>
</div>
<!--sidebar-->