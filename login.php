<?php
	//Connect to DB
	include('config.php');
	
	//If submitted update the loans and redirect to the loan page
	if(isset($_POST['submit'])) {

		$username = mysql_real_escape_string($_POST['username']);
		$password = mysql_real_escape_string($_POST['password']);
		$password = md5($password);

		$row = $db->selectObject("users", "username = '{$username}' AND password = '{$password}'");
		
		if(!empty($row)) {
			
			if($_POST['remember-me']) {
				$cookiehash = md5(sha1($row->username . $_SERVER['REMOTE_ADDR']));
				setcookie("uname",$cookiehash,time()+3600*24,'/');
				$row->login_session = $cookiehash;
				$db->updateObject($row, "users");
			}
			
			$_SESSION['ID'] = $row->id;
			$_SESSION['NAME'] = $row->firstname;
			$_SESSION['TYPE'] = $row->user_type;
			
			if($row->user_type <= 1) {
				header("location: index.php");
			} else {
				//redirect to the collector page
				header("location: index_collector.php");
			}
		} else {
			$error = 1;
		}
	}
	
?>
	<!DOCTYPE html>
	<html lang="en">
	  <head>
	    <meta charset="utf-8">
	    <title><?php echo $configs['site_title']; ?></title>
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <meta name="description" content="">
	    <meta name="author" content="">

	    <!-- Le styles -->
	    <link rel="stylesheet" href="bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" href="bootstrap/css/bootstrap-responsive.css" />
		
	<style type="text/css">
	      body {
	        padding-top: 40px;
	        padding-bottom: 40px;
	        background-color: #f5f5f5;
	      }

	      .form-signin {
	        max-width: 300px;
	        padding: 19px 29px 29px;
	        margin: 0 auto 20px;
	        background-color: #fff;
	        border: 1px solid #e5e5e5;
	        -webkit-border-radius: 5px;
	           -moz-border-radius: 5px;
	                border-radius: 5px;
	        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
	           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
	                box-shadow: 0 1px 2px rgba(0,0,0,.05);
	      }
	      .form-signin .form-signin-heading,
	      .form-signin .checkbox {
	        margin-bottom: 10px;
	      }
	      .form-signin input[type="text"],
	      .form-signin input[type="password"] {
	        font-size: 16px;
	        height: auto;
	        margin-bottom: 15px;
	        padding: 7px 9px;
	      }
	
		#footer {
			left: 48px;
			top: 95%;
			position: absolute;
		}

	    </style>
	
		<script type="text/javascript" src="bootstrap/js/jquery.js"></script>
		<script type="text/javascript" src="js/js/jquery-ui.js"></script>
		<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
		</head>

	  <body>
		<div class="container">
			
		      <form class="form-signin" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post">
			
				<?php if($error) : ?><div class="alert alert-error">Invalid username or password.</div>	<?php endif; ?>
			
		        <h2 class="form-signin-heading">Please sign in</h2>
			
		        <input placeholder="User name" type="text" name="username" id="username" required />
		        <input placeholder="Password" type="password" name="password" id="password" required />
		        <label class="checkbox">
		          <input type="checkbox" name="remember-me" value="remember-me"> Remember me
		        </label>
		
			
		        <button class="btn btn-large btn-primary" type="submit" name="submit">Sign in</button>
			
		      </form>

		</div>
		
		<div id="footer">
	      	<div class="container">
	        	<p class="muted credit">&copy; <?php echo date("Y"); ?> Commission Management System</p>
	      	</div>
		</div>
	 </body>
	</html>
