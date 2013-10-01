$(document).ready(function()
{
	$.ajaxSetup ({
    cache: false
	});
	
    
    //
    $('#account').click(function(){
    	$('#accountPopup').toggle(500);
    	$(this).toggleClass('active');
    });
    
});