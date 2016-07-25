<ul class="nav">
	
	<li class="dropdown <?php echo checkPage('index'); ?>">
		<a href="index.php">Home</a>
	</li>
	
	<li class="dropdown <?php echo checkPage('payments,createBatchPayment'); ?>">
		<a href="payments.php">Collections <b class="caret"></b></a>
	   	<ul class="dropdown-menu">	
			<li><a data-toggle="modal" href="forms/payment.php" data-target="#newPayment">Add Collection</a></li>
	    </ul>
	</li>
	
	<li class="dropdown <?php echo checkPage('clients,sortClients,addClient'); ?>">
		<a href="clients.php">Clients <b class="caret"></b></a>
	   	<ul class="dropdown-menu">
			<li><a href="addClient.php">Add Client</a></li>
	    	<li><a href="sortClients.php">Sort Clients</a></li>
	    </ul>
	</li>	
	
	<li class="dropdown <?php echo checkPage('report'); ?>">
		<a href="reports.php">Reports</a>
	</li>
</ul>