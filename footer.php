<?php if ( ! defined( 'ABSPATH' ) ) die(); ?>

    <footer id="page-footer">
        <div id="main-sidebar">
            <div class="widget-area clearfix">
                <?php
                if ( is_active_sidebar( 'sidebar-1' ) ) :
                    dynamic_sidebar( 'sidebar-1' );
                endif;
                ?>
            </div>
        </div><!-- /#main-sidebar -->

        <div id="copyright">
            <p>Copyright &copy; <?php echo date('Y'); ?>, <?php bloginfo( 'name' ); ?></p>
        </div><!-- /#copyright -->
    </footer>

    <?php wp_footer(); ?>
    <script>
        (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
        function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
        e=o.createElement(i);r=o.getElementsByTagName(i)[0];
        e.src='//www.google-analytics.com/analytics.js';
        r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
        ga('create','UA-XXXXX-X');ga('send','pageview');
    </script>
</body>
</html>