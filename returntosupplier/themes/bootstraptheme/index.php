<!DOCTYPE HTML>
<html>
    <head>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
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
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=360446907381378";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
	
        <div class="navigation navbar <?php echo (MENU_LOCATION) ? 'navbar-'.MENU_LOCATION : '' ?>">
            <div class="navbar-inner">
                <div class="container">
					
								<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					            <span class="icon-bar"></span>
					            <span class="icon-bar"></span>
					            <span class="icon-bar"></span>
					          </button>
                    <a class="brand" id="logo" href="<?php echo URL_FULL ?>"><?php echo ORGANIZATION_NAME ?></a>
					<div class="nav-collapse collapse">
                    	<?php expTheme::module(array("controller"=>"navigation","action"=>"showall","view"=>"showall_Flydown")); ?>
					
					</div>	
	<?php expTheme::module(array("controller"=>"store","action"=>"ecomSearch", "source"=>"@storeSearch")); ?>
					
                </div>
            </div>
        </div>
        <div class="navbar-spacer"></div>
        <div class="navbar-spacer-bottom"></div>
        <div class="container main-container <?php echo (MENU_LOCATION) ? 'fixedmenu' : '' ?>">
            <section id="main" class="row">
             
				<aside id="sidebar" class="span2">
					<?php expTheme::module(array("controller"=>"container","action"=>"showall","view"=>"showall","source"=>"@left")); ?>
					<?php expTheme::module(array("controller"=>"store","action"=>"showFullTree","view"=>"Default", "source"=>"@leftnav")); ?>
 				</aside>

				 <section id="content" class="span8">
					<?php expTheme::module(array("controller"=>"store","action"=>"categoryBreadcrumb")); ?>
                    <?php expTheme::main(); ?>
                 </section>

				 <aside id="sidebar2" class="span2">
					<div class="fixedcontainer">
					 <?php expTheme::module(array("controller"=>"store","action"=>"quicklinks","source"=>"@quicklinks")); ?>
					 <?php expTheme::module(array("controller"=>"container","action"=>"showall","view"=>"showall","source"=>"@right")); ?>
	                 <?php expTheme::module(array("controller"=>"store","action"=>"product_not_found","source"=>"@product_not_found")); ?>
	 				</div>
				</aside>
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
		
		<script src="<?php echo URL_FULL; ?>themes/bootstraptheme/js/main.js"></script>
                	<script src="<?php echo URL_FULL; ?>themes/bootstraptheme/js/jquery.jscroll.min.js"></script>
	
    </body>
</html>