		</div>
	</div>
	<div id="footer">
      	<div class="container">
        	<p class="muted credit">&copy; <?php echo date("Y"); ?> Commission Management System</p>
      	</div>
	</div>
	

	
	<!-- Add new payment form -->
	<div id="newPayment" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<form action="addPayment.php" method="post">
	  		<div class="modal-header">
	    		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	    		<h3 id="myModalLabel">New Collection</h3>
	  		</div>
	  		<div class="modal-body">
	   			<div class="ajaxloadercontainer"><img src="images/loader.gif" /></div>
			</div>
	  		<div class="modal-footer">
	    		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	    		<button class="btn btn-primary" name="submit_payment">Save Payment</button>
	  		</div>
		</form>
	</div>
	<!-- end -->
	
	<!-- Loan Summary -->
	<div id="loanSummary" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	
	  		<div class="modal-header">
	    		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	    		<h3 id="myModalLabel">Loan Summary</h3>
	  		</div>
	  		<div class="modal-body">
	   			<div class="ajaxloadercontainer"><img src="images/loader.gif" /></div>
			</div>
	  		<div class="modal-footer">
	    		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	  		</div>

	</div>
	<!-- end -->
	
	<!-- Payment Summary -->
	<div id="paymentSummary" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	
	  		<div class="modal-header">
	    		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	    		<h3 id="myModalLabel">Collection Summary</h3>
	  		</div>
	  		<div class="modal-body">
	   			<div class="ajaxloadercontainer"><img src="images/loader.gif" /></div>
			</div>
	  		<div class="modal-footer">
	    		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	  		</div>

	</div>
	<!-- end -->
</body>
</html>




