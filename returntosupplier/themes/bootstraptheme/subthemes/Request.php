<!DOCTYPE HTML>
<html>
    <head>
		<link rel="icon" href="<?php echo THEME_RELATIVE; ?>favicon.ico" type="image/x-icon" />
        <?php
        expTheme::head(array(
            "xhtml"=>false,
            "normalize"=>true,
            "framework"=>"bootstrap",
            "css_core"=>array(
                "common"
                ),
            "lessvars"=>array(
                'menu_height'=>MENU_HEIGHT,
            ),
            "css_links"=>true,
            "css_theme"=>true
            ));
        ?>
    </head>
    <body>
		<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	          </button>
        <div class="navigation navbar <?php echo (MENU_LOCATION) ? 'navbar-'.MENU_LOCATION : '' ?>">
            <div class="navbar-inner">
                <div class="container">
                    <a class="brand" href="<?php echo URL_FULL ?>"><?php echo ORGANIZATION_NAME ?></a>
                    <?php expTheme::module(array("controller"=>"navigation","action"=>"showall","view"=>"showall_Flydown")); ?>
					<?php expTheme::module(array("controller"=>"store","action"=>"ecomSearch", "source"=>"@storeSearch")); ?>	
                </div>
            </div>
        </div>
        <div class="navbar-spacer"></div>
        <div class="navbar-spacer-bottom"></div>
        <div class="container <?php echo (MENU_LOCATION) ? 'fixedmenu' : '' ?>">
            <section id="main" class="row">
			   	 <?php expTheme::module(array("controller"=>"container","action"=>"showall","view"=>"showall","source"=>"@request_an_item")); ?>
				 <?php expTheme::main(); ?>
            </section>
            <footer class="row">
				<hr style="background-color: #dfdfdf;" />
				<?php expTheme::module(array("controller"=>"links","action"=>"showall", "view"=>"showall_footer", "source"=>"@footer_links")) ?>
                <?php expTheme::module(array("controller"=>"text","action"=>"showall","view"=>"showall_single","source"=>"@footer","chrome"=>1)) ?>
                <?php if (MENU_LOCATION == 'fixed-bottom') echo '<div class="menu-spacer-bottom"></div>'; ?>
            </footer>
        </div>
        <?php expTheme::foot(); ?>
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-47080785-1', 'tienda.ph');
		  ga('send', 'pageview');

		</script>
    </body>
</html>
