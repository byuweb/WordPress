jQuery(document).ready($ => {

  function resizeYouTubeVideoFrames () {
    jQuery('.is_youtube_video').each(function() {
      jQuery(this).height((this.clientWidth / 16 ) * 9)
    })
  }
  resizeYouTubeVideoFrames()

  jQuery(window).resize(function() {
    resizeYouTubeVideoFrames()
  })

})
