var myApp = angular.module('myApp',['ui.select2']);

myApp.filter('translateValue', function(){
	return function(input, selData, key){
		var translated = '';
		angular.forEach(selData, function(value){
			if(value[key] == input){
				translated = value['nome'];
			}
		});
		
		return translated;
	}
});

myApp.controller('myController',function($scope){
	$scope.application = {
		"user": null
	};
	$scope.logged = false;
	$scope.template = 'login.html',
	$scope.pageTitle = 'Sistema Crossfit - Login',
	$scope.dataset = {},
	$scope.visualType = 'grid',
	$scope.form = {},
	$scope.selData = {},
	$scope.select2Options = {
		allowClear:true
	};
	$scope.loadData = function(className, callback){
		var dataset = $scope.form[className];
		$scope.callMethod(className, 'loadData', dataset, function(response){
			$scope.dataset[className] = JSON.parse(response);
			if (callback) callback(JSON.parse(response));
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
		var data = angular.copy($scope.dataset[className][index]);
		data['_STATE'] = 'U';

		$scope.form[className] = data;
		$scope.toggle('form');
	},

	$scope.deleteRow = function(className, dataset, callback){
		bootbox.dialog("Deseja realmente excluir?", [{
			"label" : "Sim",
			"class" : "btn-success",
			"callback": function() {
				var data = dataset;
				data['_STATE'] = 'D';

				Application.deleteRow(className, data, callback);

			}
		}, {
			"label" : "Cancelar"
		}]);
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

	$scope.save = function(className, dataset, callback){
		var dataset = dataset ? dataset : $scope.form[className];
		Application.saveRow(className, dataset, function(){
			$scope.loadData(className);
			//@todo remover o loadData
			if(callback) callback();
		});
		$scope.toggle('grid');
	},

	$scope.openWindow = function(className, pageTitle){
		$scope.toggle('grid');
		$scope.callWindow(className, pageTitle);
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
	};

	$scope.logOut = function(){
		if(confirm('Realmente deseja efetuar logout?')){
			location.reload();	
		}		
	};

});

myApp.controller('loginController', function($scope){
	$scope.loginData = {};
	$scope.valida = function(){
		var dataset = $scope.loginData;
		Application.request('login', 'valida', dataset, function(response){
			if(response){
				$scope.application.user = JSON.parse(response);
				$scope.setLogged(true);
				$scope.openWindow('dashboard', '');
				$scope.$apply();
			} else {
				alertMessage('error','Usuário ou senha incorretos!');
			}
		});
	}
});


myApp.controller('dashboardController', function($scope){
	$scope.dashboardData = {}
	$scope.loadData = function(){
		$scope.callMethod('dashboard', 'loadData', null, function(response){
			$scope.dashboardData = JSON.parse(response);
			$scope.$apply();
		});			
	}
});

myApp.controller('alunoController', function($scope){
	$scope.pesquisaForm = {};
	$scope.pesquisaDataset = {};
	$scope.form = {};
	$scope.selectData = {};
	$scope.loadSelects = function(){
		$scope.callMethod('aluno', 'loadSelects', null, function(response){
			$scope.selectData = JSON.parse(response);
			$scope.$apply();
		});		
	};
	$scope.pesquisa = function(){
		var dataset = $scope.pesquisaForm;
		$scope.callMethod('aluno', 'pesquisa', dataset, function(response){
			$scope.pesquisaDataset = JSON.parse(response);
			$scope.$apply();
		});		
	};
	$scope.save = function(){
		$scope.$parent.save('aluno', $scope.form, function(){
			$scope.pesquisa();
		});
	};
	$scope.newRow = function(){
		var dataset = {'_STATE':'I'};
		$scope.form = dataset;
		$scope.toggle('form');
	};
	$scope.edit = function(index){
		var dataset = angular.copy($scope.pesquisaDataset[index]);
		dataset['_STATE'] = 'U';
		$scope.form = dataset;
		$scope.toggle('form');
	};
	$scope.deleteRow = function(index){
		var dataset = $scope.pesquisaDataset[index];
		$scope.$parent.deleteRow('aluno', dataset, function(){
			$scope.pesquisa();
		});
	};
});

myApp.controller('presencaController', ["$scope", "$filter", function($scope,$filter){
	$scope.today = $filter('date')(new Date(),'dd/MM/yyyy');
	$scope.pesquisaForm = {"presenca" : {"data" : $scope.today}};
	$scope.pesquisaDataset = {};
	$scope.alunosPresente = [];
	$scope.formAula = {};
	$scope.alunosRemovidos = [];
	$scope.editing = false;
	$scope.pesquisaAluno = function(){
		var dataset = $scope.pesquisaForm['aluno'];
		$scope.callMethod('presenca', 'pesquisaAluno', dataset, function(response){
			$scope.pesquisaDataset['aluno'] = JSON.parse(response);
			$scope.$apply();
		});	
	};
	$scope.pesquisaAula = function(){
		var dataset = $scope.pesquisaForm['presenca'];
		$scope.callMethod('presenca', 'pesquisaAula', dataset, function(response){
			$scope.pesquisaDataset['aula'] = JSON.parse(response);
			$scope.$apply();
		});	
	};
	$scope.newRow = function(){
		$scope.formAula = {"data" : $scope.today, '_STATE' : 'I'};
		$scope.alunosPresente = [];
		$scope.editing = false;
		$scope.toggle('form');
	};
	$scope.addAlunoPresente = function(aluno){
		var alunoExiste = $scope.alunosPresente.filter(function(item){
			return item.id_aluno == aluno.id_aluno;
		});

		if (alunoExiste.length == 0 && $scope.alunosPresente.length < 20) {
			$scope.alunosPresente.push(aluno);	
		}
	};
	$scope.editAlunosPresentes = function(index){
		$scope.editing = true;
		$scope.alunosRemovidos = [];
		var id_aula_fk = $scope.pesquisaDataset.aula.aula_dia[index]['id_aula_fk'];
		var alunos = $scope.pesquisaDataset.aula.alunos_aula.filter(function(item){
			return item['id_aula_fk'] == id_aula_fk;
		});
		$scope.alunosPresente = angular.copy(alunos);
		$scope.formAula = angular.copy($scope.pesquisaDataset.aula.aula_dia[index]);
		$scope.formAula['_STATE'] = 'U';
		$scope.toggle('form');
	};
	$scope.cancelarEdicao = function(){
		console.log('cancelarEdicao');
		var id_aula_fk = $scope.formAula.id_aula_fk;
		var alunos = $scope.pesquisaDataset.aula.alunos_aula.filter(function(item){
			return item['id_aula_fk'] == id_aula_fk;
		});
		$scope.alunosPresente = angular.copy(alunos);
	};
	$scope.removeAlunoPresente = function(indexAluno){
		var aluno = $scope.alunosPresente.splice(indexAluno,1)[0];
		$scope.alunosRemovidos.push(aluno);
	};
	$scope.salvaAlunosPresentes = function(){
		var dataset = {"aula" : $scope.formAula, "aluno_aula" : $scope.alunosPresente, 'alunos_removidos' : $scope.alunosRemovidos};
		$scope.callMethod('presenca', 'save', dataset, function(){{
			$scope.pesquisaAula();
			$scope.toggle('grid');
			$scope.editing = false;
		}});

	};
}]);

myApp.controller('aulaExpController', function($scope){
	$scope.pesquisaForm = {};
	$scope.pesquisaDataset = {};
	$scope.form = {};
	$scope.selectData = {
		"confirmacao" : [{"id_confirmacao":1, "nome":"Confirmado"},{"id_confirmacao":2, "nome":"Não Confirmado"}],
		"presente" : [{"id_presente":1, "nome":"Presente"},{"id_presente":0, "nome":"Faltou"}]
	};
	$scope.pesquisa = function(){
		var dataset = $scope.pesquisaForm;
		$scope.callMethod('aulaexp', 'pesquisa', dataset, function(response){
			$scope.pesquisaDataset = JSON.parse(response);
			$scope.$apply();
		});		
	};
	$scope.save = function(){
		$scope.$parent.save('aulaexp', $scope.form, function(){
			$scope.pesquisa();
		});
		
	};
	$scope.newRow = function(){
		var dataset = {'_STATE':'I'};
		$scope.form = dataset;
		$scope.toggle('form');
	};
	$scope.edit = function(index){
		var dataset = angular.copy($scope.pesquisaDataset[index]);
		dataset['_STATE'] = 'U';
		$scope.form = dataset;
		$scope.toggle('form');
	};
	$scope.deleteRow = function(index){
		var dataset = $scope.pesquisaDataset[index];
		$scope.$parent.deleteRow('aulaexp', dataset, function(){
			$scope.pesquisa();
		});
	};

});

myApp.controller('fisionutriController', function($scope){
	$scope.pesquisaForm = {};
	$scope.pesquisaDataset = {};
	$scope.form = {};
	$scope.selectData = {
		"tipo" : [{"id_tipo":1, "nome":"Fisioterapia"},{"id_tipo":2, "nome":"Nutricao"}]
	};
	$scope.pesquisa = function(){
		var dataset = $scope.pesquisaForm;
		$scope.callMethod('fisionutri', 'pesquisa', dataset, function(response){
			$scope.pesquisaDataset = JSON.parse(response);
			$scope.$apply();
		});		
	};
	$scope.save = function(){
		$scope.$parent.save('fisionutri', $scope.form, function(){
			$scope.pesquisa();
		});
		
	};
	$scope.newRow = function(){
		var dataset = {'_STATE':'I'};
		$scope.form = dataset;
		$scope.toggle('form');
	};
	$scope.edit = function(index){
		var dataset = angular.copy($scope.pesquisaDataset[index]);
		dataset['_STATE'] = 'U';
		$scope.form = dataset;
		$scope.toggle('form');
	};
	$scope.deleteRow = function(index){
		var dataset = $scope.pesquisaDataset[index];
		$scope.$parent.deleteRow('fisionutri', dataset, function(){
			$scope.pesquisa();
		});
	};
});

myApp.controller('planoController', function($scope){
	$scope.pesquisaForm = {};
	$scope.pesquisaDataset = {};
	$scope.form = {};
	$scope.pesquisa = function(){
		$scope.loadData('plano', function(data){
			$scope.pesquisaDataset = data;
		});
	};
	$scope.save = function(){
		$scope.$parent.save('plano', $scope.form, function(){
			$scope.pesquisa();
		});
		
	};
	$scope.newRow = function(){
		var dataset = {'_STATE':'I'};
		$scope.form = dataset;
		$scope.toggle('form');
	};
	$scope.edit = function(index){
		var dataset = angular.copy($scope.pesquisaDataset[index]);
		dataset['_STATE'] = 'U';
		$scope.form = dataset;
		$scope.toggle('form');
	};
	$scope.deleteRow = function(index){
		var dataset = $scope.pesquisaDataset[index];
		$scope.$parent.deleteRow('plano', dataset, function(){
			$scope.pesquisa();
		});
	};
	$scope.loadData('plano', function(data){
		$scope.pesquisaDataset = data;
	});
});

myApp.controller('descontoController', function($scope){
	$scope.pesquisaForm = {};
	$scope.pesquisaDataset = {};
	$scope.form = {};
	$scope.pesquisa = function(){
		$scope.loadData('desconto', function(data){
			$scope.pesquisaDataset = data;
		});
	};
	$scope.save = function(){
		$scope.$parent.save('desconto', $scope.form, function(){
			$scope.pesquisa();
		});
		
	};
	$scope.newRow = function(){
		var dataset = {'_STATE':'I'};
		$scope.form = dataset;
		$scope.toggle('form');
	};
	$scope.edit = function(index){
		var dataset = angular.copy($scope.pesquisaDataset[index]);
		dataset['_STATE'] = 'U';
		$scope.form = dataset;
		$scope.toggle('form');
	};
	$scope.deleteRow = function(index){
		var dataset = $scope.pesquisaDataset[index];
		$scope.$parent.deleteRow('desconto', dataset, function(){
			$scope.pesquisa();
		});
	};
	$scope.loadData('desconto', function(data){
		$scope.pesquisaDataset = data;
	});
});

myApp.controller('formapagamentoController', function($scope){
	$scope.pesquisaForm = {};
	$scope.pesquisaDataset = {};
	$scope.form = {};
	$scope.pesquisa = function(){
		$scope.loadData('formapagamento', function(data){
			$scope.pesquisaDataset = data;
		});
	};
	$scope.save = function(){
		$scope.$parent.save('formapagamento', $scope.form, function(){
			$scope.pesquisa();
		});
		
	};
	$scope.newRow = function(){
		var dataset = {'_STATE':'I'};
		$scope.form = dataset;
		$scope.toggle('form');
	};
	$scope.edit = function(index){
		var dataset = angular.copy($scope.pesquisaDataset[index]);
		dataset['_STATE'] = 'U';
		$scope.form = dataset;
		$scope.toggle('form');
	};
	$scope.deleteRow = function(index){
		var dataset = $scope.pesquisaDataset[index];
		$scope.$parent.deleteRow('formapagamento', dataset, function(){
			$scope.pesquisa();
		});
	};
	$scope.loadData('formapagamento', function(data){
		$scope.pesquisaDataset = data;
	});
});

myApp.controller('estacionamentoController', function ($scope){
	$scope.pesquisaForm = {};
	$scope.pesquisaDataset = {};
	$scope.form = {};
	$scope.selectData = {};
	$scope.loadSelects = function(){
		$scope.callMethod('estacionamento', 'loadSelects', null, function(response){
			$scope.selectData = JSON.parse(response);
			$scope.$apply();
		});		
	};
	$scope.pesquisa = function(){
		var dataset = $scope.pesquisaForm;
		$scope.callMethod('estacionamento', 'pesquisa', dataset, function(response){
			$scope.pesquisaDataset = JSON.parse(response);
			$scope.$apply();
		});		
	};
	$scope.save = function(){
		$scope.$parent.save('estacionamento', $scope.form, function(){
			$scope.pesquisa();
		});
	};
	$scope.newRow = function(){
		var dataset = {'_STATE':'I'};
		$scope.form = dataset;
		$scope.toggle('form');
	};
	$scope.edit = function(index){
		var dataset = angular.copy($scope.pesquisaDataset[index]);
		dataset['_STATE'] = 'U';
		$scope.form = dataset;
		$scope.toggle('form');
	};
	$scope.deleteRow = function(index){
		var dataset = $scope.pesquisaDataset[index];
		$scope.$parent.deleteRow('estacionamento', dataset, function(){
			$scope.pesquisa();
		});
	};
});

myApp.controller('relaulaController', function ($scope) {
	$scope.pesquisaDataset = {};
	$scope.pesquisaForm = {};
	$scope.pesquisa = function(){
		var dataset = $scope.pesquisaForm;
		$scope.callMethod('relaula', 'pesquisa', dataset, function(data){
			$scope.pesquisaDataset = JSON.parse(data);
			$scope.loadChart();
		});
	};
	$scope.loadChart = function(){
		var dataValues = $scope.processData("num_presentes");
		var labelValues = $scope.processLabel("data");
		var lineChartData = {
			labels : labelValues,
			datasets : [
			{
				fillColor : "rgba(151,187,205,0.5)",
				strokeColor : "rgba(151,187,205,1)",
				pointColor : "rgba(151,187,205,1)",
				pointStrokeColor : "#fff",
				data : dataValues
			}
			]
		}

		var myLine = new Chart(document.getElementById("canvas").getContext("2d")).Bar(lineChartData);
	};
	$scope.processData = function(key){
		var values = new Array();
		angular.forEach($scope.pesquisaDataset, function(item){
			values.push(parseInt(item[key]));
		});
		return values;
	};
	$scope.processLabel = function(key){
		var values = new Array();
		angular.forEach($scope.pesquisaDataset, function(item){
			values.push(item[key]);
		});
		return values;	
	}
});

myApp.controller('relalunoController', function ($scope) {
	$scope.pesquisaDataset = {};
	$scope.pesquisaForm = {};
	$scope.selectData = {};
	$scope.loadSelects = function(){
		$scope.callMethod('relaluno', 'loadSelects', null, function(response){
			$scope.selectData = JSON.parse(response);
			$scope.$apply();
		});		
	};
	$scope.pesquisa = function(){
		var dataset = $scope.pesquisaForm;
		$scope.callMethod('relaluno', 'pesquisa', dataset, function(data){
			$scope.pesquisaDataset = JSON.parse(data);
			$scope.$apply();
		});
	};
});

myApp.controller('perfilController', function($scope){
	$scope.formPerfil = {
		'id_usuario' : $scope.application.user.id_usuario
	};

	$scope.alterarSenha = function(){
		var dataset = $scope.formPerfil;
		$scope.callMethod('perfil', 'alterarSenha', dataset, function(response){
			var message = JSON.parse(response);
			alertMessage(message.type,message.message);
		});	
	};

	$scope.alterarNome = function(){
		var dataset = $scope.formPerfil;
		$scope.callMethod('perfil', 'alterarNome', dataset, function(response){
			var message = JSON.parse(response);
			alertMessage(message.type,message.message);
		});	
	};
});

myApp.directive('uiDate', function() {
	return {
		require: '?ngModel',
		link: function($scope, element, attrs, controller) {
			controller.$render = function(){
				$.mask.definitions['d'] = '[0-3]';
				$.mask.definitions['m'] = '[0-1]';
				var value = controller.$viewValue || '';
				element.val(value);
				element.mask("d9/m9/9999", { completed : function(){
					var value = this.val();
					controller.$setViewValue(value);
				}});
			};
			return element.bind('keyup', function() {
				return $scope.$apply(function() {
					if(element.mask().length == 0){
						return controller.$setViewValue(element.mask());	
					} else {
						return controller.$setViewValue(element.val());
					}

				});
			});
		}
	};
});

myApp.directive('uiHour', function() {
	return {
		require: '?ngModel',
		link: function($scope, element, attrs, controller) {
			controller.$render = function(){
				$.mask.definitions['H'] = '[0-5]';
				$.mask.definitions['S'] = '[0-5]';
				var value = controller.$viewValue || '';
				element.val(value);
				element.mask("H9:S9", { completed : function(){
					var value = this.val();
					controller.$setViewValue(value);
				}});
			};
			return element.bind('keyup', function() {
				return $scope.$apply(function() {
					if(element.mask().length == 0){
						return controller.$setViewValue(element.mask());	
					} else {
						return controller.$setViewValue(element.val());
					}

				});
			});
		}
	};
});

myApp.directive('uiCpf', function() {
	return {
		require: '?ngModel',
		link: function($scope, element, attrs, controller) {
			controller.$render = function(){
				var value = controller.$viewValue || '';
				element.val(value);
				element.mask("999.999.999-99", { completed : function(){
					var value = this.val();
					controller.$setViewValue(value);
				}});
			};
			return element.bind('keyup', function() {
				return $scope.$apply(function() {
					if(element.mask().length == 0){
						return controller.$setViewValue(element.mask());	
					}

				});
			});
		}
	};
});

myApp.directive('uiCep', function() {
	return {
		require: '?ngModel',
		link: function($scope, element, attrs, controller) {
			controller.$render = function(){
				var value = controller.$viewValue || '';
				element.val(value);
				element.mask("99999-999", { completed : function(){
					var value = this.val();
					controller.$setViewValue(value);

					var cep = $('#cep').val();
				    var url = 'http://xtends.com.br/webservices/cep/json/'+cep+'/';    
				    
				    $.post(url,{cep:cep},
				        function (rs) {
				            rs = $.parseJSON(rs);
				            if(rs.result == 1){
				            	$scope.form.logradouro = rs.logradouro;
				            	$scope.form.bairro = rs.bairro;
				            	$scope.form.cidade = rs.cidade;
				            	$scope.form.uf = rs.uf;
				            	$scope.$apply();
				                $('#cep').removeClass('invalid');
				                last_cep = cep;
				            }
				            else{
				                $('#cep').addClass('invalid');    
				                $('#cep').focus();  
				                last_cep = 0;
				            }
				        })   
				}});
			};
			return element.bind('keyup', function() {
				return $scope.$apply(function() {
					if(element.mask().length == 0){
						return controller.$setViewValue(element.mask());	
					}

				});
			});
		}
	};
});

myApp.directive('uiRg', function() {
	return {
		require: '?ngModel',
		link: function($scope, element, attrs, controller) {
			controller.$render = function(){
				var value = controller.$viewValue || '';
				element.val(value);
				element.mask("99.999.999", { completed : function(){
					var value = this.val();
					controller.$setViewValue(value);
				}});
			};
			return element.bind('keyup', function() {
				return $scope.$apply(function() {
					if(element.mask().length == 0){
						return controller.$setViewValue(element.mask());	
					}

				});
			});
		}
	};
});

myApp.directive('uiPlaca', function() {
	return {
		require: '?ngModel',
		link: function($scope, element, attrs, controller) {
			controller.$render = function(){
				var value = controller.$viewValue || '';
				$.mask.definitions['z'] = '[a-zA-Z]';
				element.val(value);
				element.mask("zzz-9999", { completed : function(){
					var value = this.val();
					controller.$setViewValue(value);
				}});
			};
			return element.bind('keyup', function() {
				return $scope.$apply(function() {
					if(element.mask().length == 0){
						return controller.$setViewValue(element.mask());	
					}

				});
			});
		}
	};
});

myApp.directive('uiPhone', function() {
	return {
		require: '?ngModel',
		link: function($scope, element, attrs, controller) {
			controller.$render = function(){
				var value = controller.$viewValue || '';
				element.val(value);
				element.mask("(99)9999-9999", { completed : function(){
					var value = this.val();
					controller.$setViewValue(value);
				}});
			};
			return element.bind('keyup', function() {
				return $scope.$apply(function() {
					if(element.mask().length == 0){
						return controller.$setViewValue(element.mask());	
					}

				});
			});
		}
	};
});