$(function() {
	/* Navigiation interactions
    
	1- menu toggle
	2- dropdown toggle*/
	//menu toggle 
	$(".navbar-toggler").on("click", function() {
		let toggler = $(this).attr("data-target");
		$(toggler).toggleClass("open");
	})
	//dropdown toggle 
	$(".navbar .dropdown > a").on("click", function(e) {
		e.preventDefault();
		$(this).parent().toggleClass("open");
	})
})