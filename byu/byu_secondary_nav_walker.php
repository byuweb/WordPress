<?php
	class byu_secondary_nav_walker extends Walker_Nav_Menu{
		
	function start_lvl(&$output, $depth) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"sub\">\n";
	}
}