var myApp = angular.module('myApp',[]);
myApp.controller('myController',function($scope){
	$scope.logged = false;
	$scope.template = 'login.html',
	$scope.pageTitle = 'Sistema Crossfit - Login',
	$scope.dataset = {},
	$scope.form = {},
	$scope.loadData = function(className){
		Application.loadData(className, function(){
			$scope.dataset[className] = (JSON.parse(Application.dataset[className]));
			$scope.$apply();
		});			
	},

	$scope.edit = function(className, index){
		var data = $scope.dataset[className][index];
		var form = $('#'+className+"_form");

		angular.forEach(data, function(value,key){
			var field = form.find("[name = '"+key+"']");
			if(field.length){
				field.val(value);	
			}
		});

		data['_STATE'] = 'U';
		
		form.data({dataset : data});
	},

	$scope.delete = function(className, index){
		bootbox.dialog("Deseja realmente excluir?", [{
			"label" : "Sim",
			"class" : "btn-success",
			"callback": function() {
				var data = $scope.dataset[className][index];
				data['_STATE'] = 'D';

				Application.deleteRow(className, data);
			}
		}, {
			"label" : "Cancelar"
		}]);
	},

	$scope.save = function(className){
		var form = $('#'+className+"_form");
		var dataset = form.data('dataset') ? form.data('dataset') : {};
		var values = Application.getDataset(form);
		
		angular.extend(dataset,values);

		Application.saveRow(className, dataset);

	},

	$scope.openWindow = function(className, pageTitle){
		$scope.template = className+".html";
		$scope.pageTitle = pageTitle;
		$scope.loadData(className);
	},

	$scope.callMethod = function(className, methodToCall, responseHandler){
		var form = $("#"+className).parents("form");
		var dataset = Application.getDataset(form);

		Application.request(className, methodToCall, dataset, responseHandler);
	},

	$scope.setLogged = function(logged){
		$scope.logged = logged;
	}

});

myApp.controller('loginController', function($scope){
	$scope.valida = function(){
		var form = $("#login_form");
		var dataset = form.serializeObject();


		Application.request('login', 'valida', dataset, function(response){
			if(response == true){
				$scope.setLogged(true);
				$scope.openWindow('dashboard', 'Crossfit Dashboard');
			} else {
				alertMessage('error','Usuário ou senha incorretos!');
			}
		});
	}
});
