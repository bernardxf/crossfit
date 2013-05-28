var myApp = angular.module('myApp',[]);
myApp.controller('myController',function($scope){
	$scope.logged = false;
	$scope.template = 'login.html',
	$scope.pageTitle = 'Sistema Crossfit - Login',
	$scope.dataset = {},
	$scope.visualType = 'grid',
	$scope.form = {},
	$scope.loadData = function(className){
		Application.loadData(className, function(){
			$scope.dataset[className] = (JSON.parse(Application.dataset[className]));
			$scope.$apply();
		});			
	},

	$scope.newRow = function(className){
		$scope.toggle('form');
		var data = {"_STATE" : "I"};
		$scope.form[className] = data;
	}

	$scope.edit = function(className, index){
		$scope.toggle('form');

		var data = $scope.dataset[className][index];
		data['_STATE'] = 'U';

		$scope.form[className] = data;
	},

	$scope.delete = function(className, index){
		bootbox.dialog("Deseja realmente excluir?", [{
			"label" : "Sim",
			"class" : "btn-success",
			"callback": function() {
				var data = $scope.dataset[className][index];
				data['_STATE'] = 'D';

				Application.deleteRow(className, data, function(){
					$scope.loadData(className);
				});

			}
		}, {
			"label" : "Cancelar"
		}]);
	},

	$scope.save = function(className){
		var dataset = $scope.form[className];
		Application.saveRow(className, dataset, function(){
			$scope.loadData(className);
		});
		$scope.toggle('grid');
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
	},

	$scope.toggle = function(type){
		$scope.visualType = type;
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
