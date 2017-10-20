/**
 * Scans the page for any code blocks in a standard body copy section and ensures some
 * matching heights.
 */
jQuery(document).ready(function (event) {
	// Initial load
	setTimeout(adjustBodyCopyCodeLineHeights, 100);

	// Whenever resizing the window
	jQuery(window).resize(adjustBodyCopyCodeLineHeights);
})

/**
 * Looks at the height of the code which varies based on window width, its availabled width
 * and how long the line of code is. It reflects this height onto the the corresponding line
 * number to its left
 */
function adjustBodyCopyCodeLineHeights() {

	// Grab every code block within a standard body copy and iterate through them
	jQuery('.standard-body-copy code').each(function() {

		// Code Lines
		var codeLinesWrap = jQuery(this).children('.code__code-lines')[0];
		var codeLines = jQuery(codeLinesWrap).children('span');

		// Code Line Numbers
		var codeLineNumbersWrap = jQuery(this).children('.code__line-numbers')[0];
		var codeLineNumbers = jQuery(codeLineNumbersWrap).children('span');

		// Ensure same heights between line number side and code side
		for (var i = 0; i < codeLines.length; i++) {
			codeLineNumbers[i].clientHeight = codeLines[i].clientHeight;
			jQuery(codeLineNumbers[i]).css('height', codeLines[i].clientHeight + 'px');
		}

	});

}

