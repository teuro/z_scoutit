$(document).ready(function() {
	$('.datepicker').datepicker({
		constrainInput: true,   // prevent letters in the input field
		minDate: new Date(),    // prevent selection of date older than today
		showOn: 'button',       // Show a button next to the text-field
		autoSize: true,         // automatically resize the input field 
		dateFormat: 'yy-mm-dd',  // Date Format used
		dayNamesMin: ['su', 'ma', 'ti', 'ke', 'to', 'pe', 'la'],
		firstDay: 1 // Start with Monday
})

	
	$(".datepicker").trigger("click");
	
	$("input").blur(function() {
		if ($(this).parent().attr("name") == "requireAlpha" && $(this).val().search(/[^a-z\s]/) >= 0) {
			$(this).parent().attr("class", "ok");
		}
	});
	
	$("#yllapito").show();
	$("#yllapito").hide();
	
	$("#tyokalut").click(function() {
		if ($(this).text() == "nayta") {
			$(this).text("piilota");
			$(this).attr("title", "Työkalut piiloon");
		} else {
			$(this).text("nayta");
			$(this).attr("title", "Työkalut esille");
		}
		$("#yllapito").toggle();
	});
});