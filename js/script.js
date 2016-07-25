$(function() {

		var users = {};
		var userLabels = [];    

		var searchPeople = _.debounce(function(  query, process ) {

		//the "process" argument is a callback, expecting an array of values (strings) to display

		//get the data to populate the typeahead (plus some) 
		//from your api, wherever that may be
		$.get( 'lib/ajax/ajaxgetclient.php', { q: query }, function ( data ) {
		//reset these containers
			users = {};
			userLabels = [];

			//for each item returned, if the display name is already included 
			//(e.g. multiple "John Smith" records) then add a unique value to the end
			//so that the user can tell them apart. Using underscore.js for a functional approach.  
				_.each( data, function( item, ix, list ) {
					if ( _.contains( users, item.name ) ){
						item.name = item.name + ' #' + item.id;
					}

					//also store a mapping to get from label back to ID
					users[ item.name ] = {
						id: item.id,
						name: item.name,
						image: item.image,
						collector: item.collector
					};

					//add the label to the display array
					userLabels.push( item.name );
				});

				//return the display array	
				process( userLabels );

			});

		}, 300);


		$( "#user-input-header" ).typeahead( {
			source: function ( query, process ) { searchPeople( query, process );},
			updater: function (item) {
				window.location.replace("client.php?id=" + users[ item ].id);
			},
			matcher: function () { return true; },
			highlighter: function(item){
				var p = users[ item ];
				var itm = ''
					+ "<div class='typeahead_wrapper'>"
					+ "<img class='typeahead_photo' src='" + p.image + "' />"
					+ "<div class='typeahead_labels'>"
					+ "<div class='typeahead_primary'>" + p.name + "</div>"
					+ "<div class='typeahead_secondary'>" + p.collector + "</div>"
					+ "</div>"
					+ "</div>";
				return itm;
			 }
		});	
		
		$(".number").keyup(function () { 
		    this.value = this.value.replace(/[^0-9\.]/g,'');
		});

});