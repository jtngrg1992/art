app.controller('requestData',function($scope,$http,$rootScope,fetch){
		// url='api.php/?paintings&page='+page;
		// $rootScope.hide=true;
		// // alert(url); 
		// $http.get(url).success( function(response) {
  //    	$rootScope.data = response.data;
  //    	console.log($scope.data);
  //    	 });
		$scope.next=function(){
			$rootScope.loading=false;
			if($rootScope.artists==false){
				artistPage=artistPage+1;
				url='api.php/?artist='+$rootScope.selectedartist+'&page='+artistPage;
				console.log(url);
			}
			else{
				page=page+1;
				url='api.php/?paintings&page='+page;
			}
			$http.get(url).success( function(response) {
			if(response.data.length==0){
				alert("No more results to show");
				$rootScope.loading=true;
				artistPage=artistPage-1;
				return;
			}
     		$rootScope.data = response.data;
     		$rootScope.loading=true;
     		console.log($rootScope.data);
     	
		});
	};
		$scope.prev=function(){
			$rootScope.loading=false;
			if($rootScope.artists==false){
				artistPage=artistPage-1;
				if(artistPage<1){
					alert("No more results to show");
					$rootScope.loading=true;
					artistPage=artistPage+1;
					return;
				}
				url='api.php/?artist='+$rootScope.selectedartist+'&page='+artistPage;
				console.log(url);
			}
			else{
			page=page-1;
			if(page<1){
				alert("No more results to show");
				$rootScope.loading=true;
				page=page+1;
			}
			url='api.php/?paintings&page='+page;
		}
			$http.get(url).success( function(response) {

     		$rootScope.data = response.data;
     		$rootScope.loading=true;
     		console.log($rootScope.data);
     	
		});
		};

		
});

app.controller('requestArtists',function($scope,$http,$rootScope,fetch){
	$scope.artists=['Select an artist name to filter out data'];
	$scope.selectedartist=$scope.artists[0]
	$rootScope.loading=true;
	$http.get('api.php/?artists').success(function(response){
		for(i=0;i<response.data.length;i++)
			$scope.artists.push(response.data[i].NAME);
		});
		$scope.update=function(){
			$rootScope.selectedartist=$scope.selectedartist;
			artistPage=1;
			$rootScope.loading=false;
			$rootScope.artists=false;
			url='api.php/?artist='+$scope.selectedartist+'&page=1';
			$http.get(url).success(function(response){
				$rootScope.data = response.data;
				$rootScope.loading=true;
				console.log($rootScope.data);
		});
		
	};
	$scope.showAll=function(){
		$rootScope.loading=false
		page=1;
		url='api.php/?paintings&page='+page;
		fetch.getData(url).then(function(data){
			$rootScope.data = data.data;
			$rootScope.hide=true;
     		$rootScope.loading=true;
		});
			
     		
     	

	
};
});

app.controller('getDetail',function($scope, $routeParams,$rootScope,$location,$http){
	$scope.cflag=false;
	$scope.details=$routeParams.id;
	for (i=0;i<$rootScope.data.length;i++){
		if(parseInt($rootScope.data[i].ID)==parseInt($scope.details))
			break;
	}
	$scope.details=$rootScope.data[i];
	$scope.goBack=function(){
		$location.url("/");
	};

	
	$scope.recordChange=function(){
		$scope.cflag=true;
	}

	$scope.markOK=function(x){
		$rootScope.loading=false;
		date=new Date();
		url='api.php?markok=' + x + ',' + date;
		console.log(url);
		$http.get(url).success(function(response){
			if(response.success=="true")
				alert("Marked Ok");
			else
				alert("Failure. Error Encountered");
			$rootScope.loading=true;
		});
	};

	$scope.markNotOk=function(x){
		$rootScope.loading=false;
		date=new Date();
		url='api.php?marknotok=' + x + ',' + date;
		console.log(url);
		$http.get(url).success(function(response){
			if(response.success=="true")
				alert("Marked Not-Ok");
			else
				alert("Failure. Error Encountered");
			$rootScope.loading=true;
		});
	};

	$scope.save=function(x){
		$rootScope.loading=false;
		date=new Date();
		$scope.details.TIMESTAMP=date;
		$http.post("api.php?save",JSON.stringify($scope.details)).success(function(response){
			$rootScope.loading=true;
			if(response['success']=="true"){
				alert("Saved Successfully");
				window.location.assign("#/");
			}
			else
				alert("error Encountered while Saving");
		});
		// url='api.php?save=' + JSON.stringify($scope.details) + '|' + date;
		// console.log(url);
		// $http.get(url).success(function(response){
		// 	if(response.success=='true')
		// 		alert("Saved Successfully");

		// 	else
		// 		alert("Error Encountered while saving");
		// 	$rootScope.loading=true;
		// });
	};
});

app.controller('test',function($scope,fetch){
	url='api.php/?paintings&page=1';
	fetch.getData(url).then(function(data){
		$scope.test=data;
		console.log($scope.test);

	})

});