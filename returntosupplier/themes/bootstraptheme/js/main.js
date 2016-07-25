$(document).ready(function(){
         
  	jQuery.fn.exists = function(){
		return this.length > 0;
	}

	//Display the first level menu
	$(".has-child > .open-close").click(function(){
		$(this).parent(".has-child").children("ul").slideToggle();
		$(this).parent(".has-child").find("ul li.subopenli > .drop").css("display", "none");
	});
	
	//Display the second level menu
	$(".cat-navigation > ul li a").click(function(e) {	
		if( $(this).parent("li").find("ul").length ) {
			if($(this).parent("li").find("ul").css("display") == "none") {
				e.preventDefault();
				$(this).parent("li").parent("ul").find("> li ul").slideUp();
				$(this).parent("li").find("> ul").slideDown();
			}
		}
	});
});