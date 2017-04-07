<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 */
?>

</main><!-- #content -->

<!-- <?php pgsf_get_byu_footer(); ?> -->


<byu-footer class= "nocontent">

<?php pgsf_get_byu_footer(); ?>

</byu-footer>



<?php $url = get_site_url(); ?>


<!-- Google analytics Development code-->
<?php if (false !== strpos($url,'beta')) { ?>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', '<?php echo get_site_option('ga_analytics_development');?>', 'auto');
  ga('send', 'pageview');

</script>


<!-- Google analytics Production code-->

 <?php } elseif(false === strpos($url,'beta') && false === strpos($url, 'alpha') && false === strpos($url,'localhost')){ ?>

 <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', '<?php echo get_site_option('ga_analytics_production');?>', 'auto');
  ga('send', 'pageview');
</script>
<?php } else{

	}?>



<!-- Google Search Development code-->
<?php

if (false !== strpos($url,'localhost')  || false !== strpos($url,'alpha')) { ?>

	<script>
  (function() {
    var cx = '<?php echo get_site_option('ga_search_development');?>';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
  })();
</script>
<?php 

} else{ ?>


<!-- Google Search Production code-->
<script>
  (function() {
    var cx = '<?php echo get_site_option('ga_search_production');?>';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
  })();

</script>

<?php } ?>

<?php wp_footer(); ?>

</body>
</html>
