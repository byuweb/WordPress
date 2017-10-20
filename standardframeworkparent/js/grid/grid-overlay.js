jQuery(document).ready($ => {
  // Show grid when 'G' is pressed
  const gKeyCode = 71
  $(document).on('keyup', e => {
      if (e.keyCode === gKeyCode) {
          $('.show-grid').toggleClass('on')
      }
  })
})
