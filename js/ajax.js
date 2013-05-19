$(document).ready(function(){

	// PERFIL - Alterar Senha
	$('#btnSalvarTrocarSenha').click(function(e){				
	  	if($('#senhaatual').val() == "" || $('#senhanova').val() == "" || $('#confirmacao').val() == ""){
		  	alertMessage("error","Favor preencher todos os campos!");
	  	} else if($('#senhanova').val() != $('#confirmacao').val()){
	  		alertMessage("error","Nova senha está diferente da confirmação!");
	  	} else {
	  		$.post("_class/perfil.php",
            $("#formTrocarSenha").serialize(),
            function(response){
                if (response == "true"){
                    alertMessage("success",'Senha alterada com sucesso!');
                } else {
                    alertMessage("error",response);
                }
            });
	  	}		
	});

	// PERFIL - Alterar Nome
	$('#btnSalvarTrocarNome').click(function(e){				
	  	if($('#nome').value == "" || $('#senha').value == ""){
		  	alertMessage("error","Favor preencher todos os campos!");
	  	} else {
	  		$.post("_class/perfil.php",
            $("#formTrocarNome").serialize(),
            function(response){
                if (response == "true"){
                    alertMessage("success",'Nome alterado com sucesso!');
                } else {
                    alertMessage("error",response);
                }
            });
	  	}		
	});

});