jQuery(document).ready($ => {
// BreakPoint -- Background Image
// /* =============================== */
  let imageDivs = $('.breakpoint-swap--image-background')
  for (let it = 0; it < imageDivs.size(); it++) {
    const width = imageDivs[it]['clientWidth']
    const largestLoaded = 0
    pgsfBreakpointSetNewImageBackground(width, largestLoaded, imageDivs[it])
  }

  $(window).resize(() => {
    let imageDivs = $('.breakpoint-swap--image-background')
    for (let it = 0; it < imageDivs.size(); it++) {
      const width = imageDivs[it]['clientWidth']
      const largestLoaded = $(imageDivs[it]).attr('data-largest-loaded') / 1
      pgsfBreakpointSetNewImageBackground(width, largestLoaded, imageDivs[it])
    }
  })
  /**
   * Description:
   * Sets the new background image of the specified element according to the image urls found in the data attributes of the element.
   * Allows for 1 picture break.
   *
   * Expects:
   * width -> integer
   * largestLoaded -> integer
   * image -> DOM element
   * pictureBreak -> integer
   *
   * Returns: NONE
   *
   * Note:
   * These are using the standard crops for any image uploaded to the library.
   */

  function pgsfBreakpointSetNewImageBackground (width, largestLoaded, image) {
    const backgroundImage = 'background-image'

    if (width > 1024) {
      $(image).css(backgroundImage, 'url(' + $(image).attr('data-image-xlg') + ')')
      $(image).attr('data-largest-loaded', 1440)
    } else if (width > 800) {
      $(image).css(backgroundImage, 'url(' + $(image).attr('data-image-lg') + ')')
      $(image).attr('data-largest-loaded', 1024)
    } else if (width > 640) {
      $(image).css(backgroundImage, 'url(' + $(image).attr('data-image-mlg') + ')')
      $(image).attr('data-largest-loaded', 800)
    } else if (width > 375) {
      $(image).css(backgroundImage, 'url(' + $(image).attr('data-image-med') + ')')
      $(image).attr('data-largest-loaded', 640)
    } else {
      $(image).css(backgroundImage, 'url(' + $(image).attr('data-image-sm') + ')')
      $(image).attr('data-largest-loaded', 375)
    }
  }
})
