<div class="product product_not_found">
	
    <p>Can't find what you're looking for?</p>

    <h3 class="product-info">
        <a href="javascript:;" class="awesome medium red request_product">Request it</a>
    </h3>   
<div class="yui3-skin-sam">
	<div id="panelContent" class="panelContent deals hide">
		 <div class="module forms edit enter-data" id="yui_3_11_0_1_1389606041608_64">
			<h1>Request an Item</h1>                           
			 	<div class="bodycopy" id="yui_3_11_0_1_1389606041608_63">
					<div class="error"></div>
					<form id="request-an-item" name="form" method="post" action="/tienda/index.php" enctype="">
						<input type="hidden" name="action" id="action" value="confirm_data">
						<input type="hidden" name="m" id="m" value="forms">
						<input type="hidden" name="s" id="s" value="@random52d3aebef1998">
						<input type="hidden" name="i" id="i" value="">
						<input type="hidden" name="id" id="id" value="2">
						<input type="hidden" name="controller" id="controller" value="forms">
						<input type="hidden" name="src" id="src" value="@random52d3aebef1998">
						<input type="hidden" name="int" id="int" value="">
						<div id="nameControl" class="text-control control "><label class="">Name</label><input id="name" class="text" type="text" name="name" value="" size="25"></div>
						<div id="contactControl" class="text-control control "><label class="">Contact</label><input id="contact" class="text" type="text" name="contact" value="" size="25"></div>
						<div id="requestControl" class="text-control control "><label class="" id="yui_3_11_0_1_1389606041608_62">Describe what you want</label><textarea class="textarea" id="request" name="request" rows="5" cols="38"></textarea></div>
						<div id="submitControl" class="control buttongroup"><button type="submit" id="submitSubmit" class="submit btn btn-primary btn-medium finish" value="Submit" onclick="if (checkRequired(this.form)) { return true; } else { return false; }"><i class="icon-ok-circle icon-medium"></i> Submit </button></div>
					</form>
				</div>
		    </div>
		</div>
	</div>
</div>

{css unique="panel-css" corecss="panel"}
{/css}
{script unique="`$name`listajax" yui3mods="1"}
{literal}
	YUI(EXPONENT.YUI3_CONFIG).use('node', "panel", "dd-plugin", function (Y) {

		var panel = new Y.Panel({
			srcNode      : '#panelContent',
			headerContent: 'testing',
			width        : 270,
			zIndex       : 5,
			centered     : true,
			modal        : true,
			visible      : false,
			render       : true,
			plugins      : [Y.Plugin.Drag],
			hideOn: [
		            {
		                // When we don't specify a `node`,
		                // it defaults to the `boundingBox` of this Panel instance.
		                eventName: 'clickoutside'
		            }]
		});
		
		addRowBtn  = Y.one('.request_product'),
		
		 // When the addRowBtn is pressed, show the modal form.
		addRowBtn.on('click', function (e) {
			e.preventDefault();
			Y.one("#panelContent").removeClass("hide");
			panel.show();
		});
		
	});

	{/literal}
{/script}