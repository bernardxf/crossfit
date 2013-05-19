$(document).ready(function () {
	// Hide All  Alert Message Before
	$('.alertMessage').live('click',function(){
		alertHide();
	});
	// Login Click
	$('#but_login').click(function(e){				
	  	if(document.formLogin.username.value == "" || document.formLogin.password.value == ""){
		  	alertMessage("error","Campo(s) Usuário e/ou Senha vazio(s)");
	  	} else {
	  		$.post("_class/login.php",
            $("#formLogin").serialize(),
            function(response){
                if (response == "true"){
                    window.location = 'home.html';
                } else {
                    alertMessage("error",response);
                }
            });
	  	}		
	});	
});			
//Hidden All  Alert Message Before
function alertHide(){
	$('#alertMessage').each(function(index) {	 
		$(this).attr("id","alertMessage"+index).animate({ opacity: 0,right: '30'}, 500,function(){ $(this).remove(); });	
	});	
}
// Create Alert Message Box
function alertMessage(type,str){
	//Hidden All  Alert Message Before
	alertHide();
	// type is a success ,info, warning ,error
	$('body').append('<div id="alertMessage" class="alertMessage '+type+'">');
	$.alertbox=$('#alertMessage').html(str);
	$.alertbox.show().animate({ opacity: 1,right: '10' },500);
}	