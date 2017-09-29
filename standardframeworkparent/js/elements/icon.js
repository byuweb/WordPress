/**
 * Scans the page for any accordion DOM Elements and initializes the setup them.
 */
jQuery(document).click( function (event) {
  var target = event.target;
  while(target !== null && jQuery(target).hasClass('icon') === false) {
    target = target.parentNode;
  }
  if (jQuery(target).hasClass('icon') === true && jQuery(target).hasClass('icon--no-click') === false) {
    Icon.changeState(target);
  }
})

function Icon() {}

/**
 * This will change the state of the Icon. If there is no force state specified then the Icon will
 * move through its natural state progression. If there is a valid forceState Integer given for the
 * specified Icon, then no matter the current state of the icon, it will be forced to that state.
 * Note that if the Icon is already in the state to which it is being forced, the function will simply
 * return false, because it did not change anything.
 * 
 * @param {DOM Element} icon The Icon that needs to change state.
 * @param {Integer} forceState The state number to force the icon to.
 */
Icon.changeState = function(icon, forceState = 0) {
  // Verify that the give DOM element is an icon
  if(jQuery(icon).hasClass('icon') === false) {
    return false;
  }

  // Handle two state icon changes
  if (jQuery(icon).hasClass('icon--std-two-state') === true) {

    // Change to state two
    if (jQuery(icon).hasClass('icon--state-one') === true && (forceState === 0 || forceState === 2)) {
      jQuery(icon).removeClass('icon--state-one');
      jQuery(icon).addClass('icon--state-two');
      return true;
    } 

    // Change to state one
    if (jQuery(icon).hasClass('icon--state-two') === true && (forceState === 0 || forceState === 1)) {
      jQuery(icon).removeClass('icon--state-two');
      jQuery(icon).addClass('icon--state-one');
      return true;
    }
  }
}