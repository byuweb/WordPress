/***
 * The following are two examples of how to structure the output of an accordion in order for this script
 * to take effect. if the accordion has the class 'accordion--select-any' then you can have as many items
 * open at a time as you like. Each click only affects the item you click on. If the accordion has the class
 * 'accordion--select-one' then the item you click on will be toggled and all the rest will be closed.
 *
 * All this code does is change the 'accordion__item--closed' class to 'accordion__item--open' and vice-versa
 * when the item is being toggled. All changes that need to occur inside the item in (and transitioning between)
 * those two states should be based on the adding and removal of those two classes.

<div class="accordion accordion--select-any">
  <div class="accordion__item accordion__item--closed">
    <div class="accordion__item__title-bar">
      <div class="accordion__item__main-toggle accordion__item__toggle">my button</div>
      <div class="accordion__item__title-area">My Awesome title area things</div>
    </div>
    <div class="accordion__item__content"></div>
  </div>
</div>

<div class="accordion accordion--select-one">
  <div class="accordion__item accordion__item--closed">
    <div class="accordion__item__title-bar">
      <div class="accordion__item__main-toggle accordion__item__toggle">my button</div>
      <div class="accordion__item__title-area">My Awesome title area things</div>
    </div>
    <div class="accordion__item__content"></div>
  </div>
</div>

 */

/**
 * Scans the page for any accordion DOM Elements and initializes the setup them.
 */
jQuery(document).ready(function () {
  jQuery('.accordion').each(function () {
    var accordion = new Accordion(this)
  })
})

/**
 * Listens for when a new accordion is added to the page, and does the appropriate setup
 * when added.
 */
document.addEventListener('DOMNodeInserted', function (event) {
  // Verify the the Node added to the dom is a child of an accordion of of type 'accordion'
  if (jQuery(event.target).hasClass('accordion') === true) {
    var accordion = new Accordion(event.target)
    jQuery(document).trigger('accordion-added', {'insertEvent': event, 'jsAccordion': accordion, 'accordion': accordion.accordion});
  }
})

/**
 * Creates an accordion object. Emits 7 different events.
 * 1) accordion-added
 * 2) accordion-item-added
 * 3) accordion-item-toggle-added
 * 4) accordion-item-closed
 * 5) accordion-item-opened
 * 6) accordion-item-toggle-mouseenter
 * 7) accordion-item-toggle-mouseleave
 * 
 */
function Accordion(accordion) {
  // DOM Element Accordion
	this.accordion = accordion

	this.attachInsertionEvents(accordion)
  Accordion.attachHoverEvents()
}

/**
 * Detects whenever a toggle is clicked on the page and toggles the corresponsing item.
 */
jQuery(document).on('click', function(event) {
  var toggleTarget = event.target
  // Find parent toggle
  if (jQuery(toggleTarget).hasClass('accordion__item__toggle') === false) {
    toggleTarget = jQuery(toggleTarget).parents('.accordion__item__toggle')[0]
  }
  // Find parent item of toggle
  var toggleItem = undefined;
  if (toggleTarget !== undefined) {
    toggleItem = jQuery(toggleTarget).parents('.accordion__item')[0]
  }
  // toggle
  if (toggleItem !== undefined) {
    Accordion.toggleItem(toggleItem)
  }
})

/**
 * Attach events related to the accordion. Whenever the item__toggle is clicked we toggle the
 * item. Whenever a new item is inserted into the accordion, we attach the appropriate event
 * handlers.
 * 
 * @param {DOM Element} accordion The accordion to attach the events to
 */
Accordion.prototype.attachInsertionEvents = function() {
  var accordionJSObject = this

	// Appending new elements to accordion
	accordionJSObject.accordion.addEventListener('DOMNodeInserted', function (event) {

    // Verify the the Node added to the dom is a child of an accordion of of type 'accordion__item'
    if (jQuery(event.target.parentNode).hasClass('accordion') === true && jQuery(event.target).hasClass('accordion__item') === true) {
      jQuery(this).trigger('accordion-item-added', {'jsAccordion': accordionJSObject, 'item': event.target, 'accordion': accordionJSObject.accordion})
      Accordion.attachHoverEvents()
    }

    // Verify the the Node added to the dom is a child of an title-bar of of type 'accordion__item__toggle'
    if (jQuery(event.target.parentNode).hasClass('accordion__item__title-bar') === true && jQuery(event.target).hasClass('accordion__item__toggle') === true) {
      jQuery(this).trigger('accordion-item-toggle-added', {'jsAccordion': accordionJSObject, 'toggle': event.target, 'accordion': accordionJSObject.accordion})
      Accordion.attachHoverEvents()
    }
	})

}

/**
 * Reattaches a mouseenter and mouseleave event to all of the accordion__item__toggles on
 * the page. Adds and removes the 'toggle-hovered' to the item that has an accordion__item__toggle
 * with a mouseenter or mouseleave event.
 */
Accordion.attachHoverEvents = function() {
  jQuery(document).find('.accordion__item__toggle').each(function() {
    jQuery(this).off('mouseenter')
    jQuery(this).on('mouseenter', function(event) {
      var parentItem = jQuery(event.target).parents('.accordion__item')[0]
      var accordion = jQuery(parentItem).parent()
      var titleBar = jQuery(parentItem).children('.accordion__item__title-bar')[0]
      var mainToggle = jQuery(titleBar).children('.accordion__item__main-toggle')[0]
      jQuery(parentItem).addClass('toggle-hovered').trigger('accordion-item-toggle-mouseenter', {'accordion': accordion, 'item': parentItem, 'toggle': this, 'mainToggle': mainToggle });
    })

    jQuery(this).off('mouseleave')
    jQuery(this).on('mouseleave', function(event) {
      var parentItem = jQuery(event.target).parents('.accordion__item')[0]
      var accordion = jQuery(parentItem).parent()
      var titleBar = jQuery(parentItem).children('.accordion__item__title-bar')[0]
      var mainToggle = jQuery(titleBar).children('.accordion__item__main-toggle')[0]
      jQuery(parentItem).removeClass('toggle-hovered').trigger('accordion-item-toggle-mouseleave', {'accordion': accordion, 'item': parentItem, 'toggle': this, 'mainToggle': mainToggle });
    })
  })
}

/**
 * Toggles the display of the accordion__item. If closed, it will open, if open it will close.
 * if the accordion has the accordion--select-one class, all other items will close. if the
 * accordion has the accordion--select-any class, all other items will stay in their current
 * state.
 * 
 * @param {DOM Element} item The accordion__item to toggle.
 */
Accordion.toggleItem = function(item) {
  if (jQuery(item).hasClass('accordion__item') === false) {
    return;
  }

  var accordion = jQuery(item).parent()[0]
  if (jQuery(accordion).hasClass('accordion') === false) {
    return;
  }

  // Handle the select one variation
  if(jQuery(accordion).hasClass('accordion--select-one')) {
    if (jQuery(item).hasClass('accordion__item--closed') === true) {
      Accordion.closeAllItems(accordion)
      Accordion.openItem(item)
    } else {
      Accordion.closeAllItems(accordion)
    }
  } 

  // Handle the select any variation
  else if(jQuery(accordion).hasClass('accordion--select-any')) {
    if (jQuery(item).hasClass('accordion__item--open') === true) {
      Accordion.closeItem(item)
    } else if (jQuery(item).hasClass('accordion__item--closed') === true) {
      Accordion.openItem(item)
    }
  }
}

/**
 * Closes all the items of the given accordion DOM Element
 * 
 * @param {DOM Element} target The accordion to completely close.
 */
Accordion.closeAllItems = function(target) {
  if(jQuery(target).hasClass('accordion')) {
    jQuery(target).children('.accordion__item').each(function() {
      Accordion.closeItem(this)
    })
  }
}

/**
 * Closes the accordion__item.
 * 
 * @param {DOM Element} item The accordion__item to close.
 */
Accordion.closeItem = function(item) {
  if(jQuery(item).hasClass('accordion__item') && jQuery(item).hasClass('accordion__item--open')) {
    var accordion = jQuery(item).parent()
    jQuery(item).removeClass('accordion__item--open')
    jQuery(item).addClass('accordion__item--closed').trigger('accordion-item-closed', {'accordion': accordion, 'item':item})
  }
}

/**
 * Opens the accordion__item.
 * 
 * @param {DOM Element} item The accordion__item to open.
 */
Accordion.openItem = function(item) {
  if(jQuery(item).hasClass('accordion__item') && jQuery(item).hasClass('accordion__item--closed')) {
    var accordion = jQuery(item).parent()
    jQuery(item).removeClass('accordion__item--closed')
    jQuery(item).addClass('accordion__item--open').trigger('accordion-item-opened', {'accordion': accordion, 'item':item})
  }
}
