<?php
	//Connect to DB
	include('config.php');
	
	//If submitted update the loans and redirect to the loan page
	if(isset($_POST['submit'])) {
		$username  = mysql_real_escape_string($_POST['username']);
		$password  = mysql_real_escape_string($_POST['password']);
		$password2 = mysql_real_escape_string($_POST['password2']);
		
		if($password == $password2) {
			
			if(trim($password) == "") {
				$error = "Password cannot be empty.";
			} else {
				
				$user = $db->selectObject("users", "id ={$_SESSION['ID']}");
				$user->username = $username;
				$user->password = md5($password);
				$db->updateObject($user, "users");
				
				$alert = new stdclass();
				$alert->type = "success";
				$alert->msg  = "You have successfully updated your account.";
			

				$alerts[] = $alert;
				
			//	header("location: index.php");
			}
	
		} else {
			$error = "Password doesn't match.";
		}
	}
	if(!empty($error)) {
		$alert = new stdclass();
		$alert->msg  = $error;
		$alerts[] = $alert;
	}

	$user = $db->selectObject("users", "id ={$_SESSION['ID']}");
	$pageheader = "Account";
	include('header.php');
	
?>

		<div class="row-fluid">
			<div class="span3">
			
			
			</div>
			
			
			<div class="span9">
					<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post">
						<table class="table2">
							<tr>
								<td><input placeholder="Username" type="text" name="username" id="username" class="textinput" value="<?php echo $user->username; ?>" required /></td>
							</tr>
							<tr>
								<td><input placeholder="Password" type="password" name="password" id="password" class="textinput" value="" required /></td>
							</tr>
							<tr>
								<td><input placeholder="Confirm Password" type="password" name="password2" id="password2" class="textinput" value="" required /></td>
							</tr>
							<tr>
								<td><input type="submit" value="Update" name="submit" class="btn btn-primary" /></td>
							</tr>
						</table>
					</form>
			</div>
		
	
	</div>
	<!-- Include the footer -->
	<?php include('footer.php'); ?>