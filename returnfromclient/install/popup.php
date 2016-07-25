<?php

##################################################
#
# Copyright (c) 2004-2013 OIC Group, Inc.
#
# This file is part of Tienda
#
# Tienda is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################

// Jumpstart to Initialize the installer language before it's set to default

include_once('../exponent.php');

?>
<!DOCTYPE HTML>
<html>
<head>
	<title><?php echo gt('Tienda CMS : Install Wizard'); ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo LANG_CHARSET; ?>" />
	<meta name="Generator" content="Tienda Content Management System - <?php echo expVersion::getVersion(true); ?>" />
	<link rel="stylesheet" href="<?php echo YUI3_RELATIVE; ?>cssreset/cssreset.css" />
	<link rel="stylesheet" href="<?php echo YUI3_RELATIVE; ?>cssfonts/cssfonts.css" />
	<link rel="stylesheet" href="<?php echo PATH_RELATIVE; ?>framework/core/assets/css/forms.css" />
	<link rel="stylesheet" href="<?php echo PATH_RELATIVE; ?>framework/core/assets/css/button.css" />
	<link rel="stylesheet" href="<?php echo PATH_RELATIVE; ?>framework/core/assets/css/tables.css" />
	<link rel="stylesheet" href="<?php echo PATH_RELATIVE; ?>framework/core/assets/css/common.css" />
	<link rel="stylesheet" title="exponent" href="style.css" />
</head>
<body>
	<div class="popup_content_area">
		<?php
		$page = (isset($_REQUEST['page']) ? expString::sanitize($_REQUEST['page']) : '');
		if (is_readable('popups/'.$page.'.php')) include('popups/'.$page.'.php');
		?>
	</div>
</body>
</html>