<?php

// Include the js reference_object tool... :)
add_action( 'wp_head', 'pgsf_js_tool_reference_object', 10 );
function pgsf_js_tool_reference_object() {

	?>
	<script>
	function PgsfJsRefObject() {}
	PgsfJsRefObject.decodeEscapedHTML = function(string) {
		var conversions = [
			{ from: '&lt;', to: '<'},
			{ from: '&gt;', to: '>'},
			{ from: '&quot;', to: '\"'},
		];
		for(var i in conversions) {
			var tempString = string.replace(new RegExp(conversions[i]['from'], 'g'), conversions[i]['to']);
			string = tempString;
		}
		return string;
	}
	</script>
	<?php
}

/**
 * Sets some variables and functions needed by the front-end javascript to generate skin 1 for the accordion tool.
 * This is to be added as an action to wp_head by the parent theme when the pgsf_use_accordion_skin('1') function is
 * called.
 */
function pgsf_js_tool_reference_object__add_accordion_skin_1() {

	$accordion_skin_1_icon_options = array(
		'shape' => 'circle',
		'extra_classes' => 'accordion__item__main-toggle accordion__item__toggle icon--std-two-state icon--no-click',
	);
	$accordion_skin_1_icon_arrow_version = 2;

	?>

	<script>
	PgsfJsRefObject.accordionSkin1Toggle = '<?php echo esc_html( get_the_icon__arrow( $accordion_skin_1_icon_options, $accordion_skin_1_icon_arrow_version ) ); ?>';
	PgsfJsRefObject.getAccordionSkinToggle = function(skinID) {
		if(skinID === 1) {
			return PgsfJsRefObject.decodeEscapedHTML(PgsfJsRefObject.accordionSkin1Toggle);
		}
	}
	</script>
	<?php
}