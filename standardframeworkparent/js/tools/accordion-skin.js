
/**
 * Scans the page for any accordion DOM Elements and initializes the setup them.
 */
jQuery(document).ready(function () {
  jQuery('.accordion--skin-1').each(function () {
    jQuery(this).children('.accordion__item').each(function() {
      AccordionSkin1.addToggle(this);
    })
  })
})

/**
 * Javscript object with necessary methods and data for the Accordion skin 1 styling.
 */
function AccordionSkin1() {}

/**
 * Attempts to add a toggle Icon to the given item. If there is already a toggle as a child of the 
 * accordion__item__title-bar then another toggle will not be added. 
 * 
 * @param {DOM Element} item The accordion__item to add the toggle to. 
 */
AccordionSkin1.addToggle = function(item) {
  // If there is a valid item given
  if(jQuery(item).hasClass('accordion__item') === false) {
    return false;
  }

  // If there is no title bar
  var titleBar = jQuery(item).children('.accordion__item__title-bar')[0];
  if (titleBar === undefined) {
    return false;
  }
  // If there is already an itemToggle present
  var itemToggle = jQuery(titleBar).children('.accordion__item__toggle')[0];
  if (itemToggle !== undefined) {
    return false;
  }

  jQuery(titleBar).prepend(PgsfJsRefObject.getAccordionSkinToggle(1));
  return true;
}
/**
 * Called every time an accordion is added to the DOM.
 * 
 * @param {event object} event The event information concerning the accordion that closed.
 * @param {object} data Extra data regarding the event.
 */
jQuery(document).on('accordion-added', function(event, data) {
  if (jQuery(data.accordion).hasClass('accordion--skin-1') === false) {
    return;
  }
  jQuery(data.insertEvent.target).children('.accordion__item').each(function() {
    AccordionSkin1.addToggle(this);
  })
});

/**
 * Whenever there is an item added to the accordion (like with ajax) after the initial page load
 * attempt to add the appropriate toggle.
 *
 * @param {event object} event The event information concerning the item that was added to the accordion.
 * @param {object} data Extra data regarding the event.
 */
jQuery(document).on('accordion-item-added', function(event, data) {
  if (jQuery(data.accordion).hasClass('accordion--skin-1') === false) {
    return;
  }
  if(jQuery(event.target).hasClass('accordion--skin-1') === true) {
    AccordionSkin1.addToggle(data.item)
  }});

/**
 * Called every time an accordion__item__toggle is added to the DOM.
 * 
 * @param {event object} event The event information concerning the accordion__item__toggle that closed.
 * @param {object} data Extra data regarding the event.
 */
jQuery(document).on('accordion-item-toggle-added', function(event, data) {
  if (jQuery(data.accordion).hasClass('accordion--skin-1') === false) {
    return;
  }
  // Do stuff 
});

/**
 * Called every time an item within an accordion--skin-1 is closed. This forces the state of the icon
 * being used for the toggle to its closed display state.
 *
 * @param {event object} event The event information concerning the item that closed.
 * @param {object} data Extra data regarding the event.
 */
jQuery(document).on('accordion-item-closed', function(event, data) {
  if (jQuery(data.accordion).hasClass('accordion--skin-1') === false) {
    return;
  }
  var titleBar = jQuery(event.target).children('.accordion__item__title-bar')[0];
  var icon = jQuery(titleBar).children('.icon')[0];
  Icon.changeState(icon, 1);
});

/**
 * Called every time an item within an accordion--skin-1 is opened. This forces the state of the icon
 * being used for the toggle to its opened display state.
 *
 * @param {event object} event The event information concerning the item that opened.
 * @param {object} data Extra data regarding the event.
 */
jQuery(document).on('accordion-item-opened', function(event, data) {
  if (jQuery(data.accordion).hasClass('accordion--skin-1') === false) {
    return;
  }
  var itemContent = jQuery(data.item).children('.accordion__item__content')[0];
  jQuery(itemContent).hide();
  jQuery(itemContent).fadeIn();
});

/**
 * Called every time an item__toggle within an accordion--skin-1 has mouseenter event.
 *
 * @param {event object} event The event information concerning the item that opened.
 * @param {object} data Extra data regarding the event.
 */
jQuery(document).on('accordion-item-toggle-mouseenter', function(event, data) {
  if (jQuery(data.accordion).hasClass('accordion--skin-1') === false) {
    return;
  }
  if (jQuery(data.item).hasClass('accordion__item--open') === true) {
    Icon.changeState(data.mainToggle, 1);
  }
});

/**
 * Called every time an item__toggle within an accordion--skin-1 has mouseleave event.
 *
 * @param {event object} event The event information concerning the item that opened.
 * @param {object} data Extra data regarding the event.
 */
jQuery(document).on('accordion-item-toggle-mouseleave', function(event, data) {
  if (jQuery(data.accordion).hasClass('accordion--skin-1') === false) {
    return;
  }
  if (jQuery(data.item).hasClass('accordion__item--open') === true) {
    Icon.changeState(data.mainToggle, 2);
  }
});
