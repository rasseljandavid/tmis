<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $configs['site_title']; ?></title>
    <link rel="icon" type="image/png" href="favicon.ico" />
	<link rel="stylesheet" href="bootstrap/css/bootstrap.css" />
	<link rel="stylesheet" href="bootstrap/css/bootstrap-responsive.css" />
	<link rel="stylesheet" href="css/jquery-ui.css" />
	<link rel="stylesheet" href="css/style.css" />
	<script type="text/javascript" src="bootstrap/js/jquery.js"></script>
	<script type="text/javascript" src="js/js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/timepicker.js"></script>
	<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/underscore-min.js"></script>
	<!-- For add client page -->
	<script type="text/javascript" src="js/jQuery-custom-input-file.js"></script>
    <script type="text/javascript" src="js/jquery.upload.js"></script>
	<!-- For add client page -->
	<script type="text/javascript" src="js/script.js"></script>
</head>
<body>
   	<div class="navbar navbar-fixed-top navbar-inverse">
	      <div class="navbar-inner">
	        <div class="container">
	          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
	        	  <span class="icon-bar"></span>
				  <span class="icon-bar"></span>
				  <span class="icon-bar"></span>
				  <span class="icon-bar"></span>
				  <span class="icon-bar"></span>
				  <span class="icon-bar"></span>
	          </button>
	
	          <a class="brand" href="index.php"><?php echo $configs['site_title']; ?></a>
	          <div class="nav-collapse collapse">
	            <?php include("navigation.php"); ?>
	           
	
				<ul class="nav pull-right">	
					<li class="<?php echo checkPage('account'); ?>"><a href="account.php" title="Edit Account"><i class="icon-user"></i></a></li>	
					<li><a href="logout.php" title="Logout"><i class="icon-off"></i></a></li>
				</ul>

				 <div class="navbar-form pull-right">
		             <form class="form-search">
						<div class="input-append">
					
							<input placeholder="Type client name here..."  type="text" id="user-input-header" autocomplete="off" class="span2 search-query" />
					
						 	<span class="add-on"><i class="icon-search"></i></span>
						
						 </div>
							<div id="container_search">
							</div>
					</form>
		         </div>
	
	          </div><!--/.nav-collapse -->
	        </div>
	      </div>
	    </div>
	<div class='hero'>
		<div class="container">
			
 			<?php echo showAlerts($alerts); ?>

			<div class="row-fluid">
				<div class="span3">
					<h3><?php echo $pageheader; ?></h3>
				</div>
			
				<div class="span9">
					<?php echo showBreadcrumbs(basename($_SERVER['PHP_SELF'], ".php"), $_GET['id']); ?>
				</div>
			</div>