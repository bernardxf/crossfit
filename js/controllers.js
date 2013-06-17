var myApp = angular.module('myApp',[]);

myApp.filter('translateValue', function(){
	return function(input, selData, className){
		var translated = '';
		angular.forEach(selData, function(value){
			if(value['id_'+className] == input){
				translated = value['nome'];
			}
		});
		
		return translated;
	}
});

myApp.controller('myController',function($scope){
	$scope.logged = false;
	$scope.template = 'login.html',
	$scope.pageTitle = 'Sistema Crossfit - Login',
	$scope.dataset = {},
	$scope.visualType = 'grid',
	$scope.form = {},
	$scope.selData = {},
	$scope.loadData = function(className, callback){
		var dataset = $scope.form[className];
		$scope.callMethod(className, 'loadData', dataset, function(response){
			$scope.dataset[className] = JSON.parse(response);
			if (callback) callback();
			$scope.$apply();
		});
	},

	$scope.loadSelects = function(className, callback){
		$scope.callMethod(className, 'loadSelects', null, function(response){
			$scope.selData[className] =  JSON.parse(response);
			if (callback) callback();
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
		$scope.toggle('grid');
		$scope.loadSelects(className, $scope.loadData(className, $scope.callWindow(className, pageTitle)));
	},
 
 	$scope.callWindow = function(className, pageTitle){
 		$scope.template = className+".html";
		$scope.pageTitle = pageTitle;
 	},

	$scope.callMethod = function(className, methodToCall, dataset, responseHandler){
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

myApp.controller('alunoController', function($scope){
	$scope.pesquisaForm = {},
	$scope.pesquisa = function(className){
		var dataset = $scope.pesquisaForm[className];
		$scope.callMethod(className, 'pesquisa', dataset, function(response){
			$scope.dataset[className] = JSON.parse(response);
			$scope.$apply();
		});
	}
});

myApp.controller('presencaController', function($scope){
	$scope.pesquisaForm = {},
	$scope.pesquisaDataset = {},
	$scope.alunosPresente = [];
	$scope.form = { 'alunos' : $scope.alunosPresente};
	$scope.pesquisaAluno = function(className){
		var dataset = $scope.pesquisaForm['aluno'];
		$scope.callMethod(className, 'pesquisaAluno', dataset, function(response){
			$scope.pesquisaDataset[className] = JSON.parse(response);
			$scope.$apply();
		});	
	},
	$scope.pesquisaPresenca = function(className){
		var dataset = $scope.pesquisaForm['presenca'];
		$scope.callMethod(className, 'pesquisaPresenca', dataset, function(response){
			$scope.pesquisaDataset[className] = JSON.parse(response);
			$scope.$apply();
		});	
	},
	$scope.addAlunoPresente = function(aluno){
		if ($.inArray(aluno, $scope.alunosPresente) == -1 && $scope.alunosPresente.length < 20) {
			$scope.alunosPresente.push(aluno);	
		}
	},
	$scope.removeAlunoPresente = function(indexAluno){
		$scope.alunosPresente.splice(indexAluno,1);
	},
	$scope.salvaAlunosPresentes = function(){
		$scope.callMethod('presenca', 'save', $scope.form);
		$scope.toggle('grid');
	}
});

/* Masked Input */
myApp.directive('uiMask', function() {
    return {
        require: 'ngModel',
        scope: {
            uiMask: '='
        },
        link: function($scope, element, attrs, controller) {
            controller.$render = function() {
                var value = controller.$viewValue || '';
                //console.log('Rendering value: ', value);
                element.val(value);
                return element.mask($scope.uiMask);
            };
            controller.$parsers.push(function(value) {
                var isValid;
                //console.log('parsing', value);
                isValid = element.data('mask-isvalid');
                //console.log('isvalid', isValid);
                controller.$setValidity('mask', isValid);
                if (isValid) {
                    console.log('returning',element.mask());
                    return element.mask();
                } else {
                    return null;
                }
            });
            return element.bind('keyup', function() {
                //console.log('change');
                return $scope.$apply(function() {
                    return controller.$setViewValue(element.mask());
                });
            });
        }
    };
});
