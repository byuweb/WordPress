<?php

get_header(); ?>

<style>
    html {
        height: 100%;
    }

    body {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .page-content {
        flex: 1;
    }
</style>

<?php
// the_body_copy_section();
the_sf_tools_page();


get_footer();
