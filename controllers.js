app.controller('requestData',function($scope,$http,$rootScope){
		// url='api.php/?paintings&page='+page;
		// $rootScope.hide=true;
		// // alert(url); 
		// $http.get(url).success( function(response) {
  //    	$rootScope.data = response.data;
  //    	console.log($scope.data);
  //    	 });
		$scope.next=function(){
			$rootScope.loading=false;
			page=page+1;
			url='api.php/?paintings&page='+page;
			$http.get(url).success( function(response) {
     		$rootScope.data = response.data;
     		$rootScope.loading=true;
     		console.log($rootScope.data);
     	
		});
	};
		$scope.prev=function(){
			$rootScope.loading=false;
			page=page-1;
			url='api.php/?paintings&page='+page;
			$http.get(url).success( function(response) {
     		$rootScope.data = response.data;
     		$rootScope.loading=true;
     		console.log($rootScope.data);
     	
		});
		};

		
});

app.controller('requestArtists',function($scope,$http,$rootScope){
	$scope.artists=['Select an artist name to filter out data'];
	$scope.selectedartist=$scope.artists[0]
	$rootScope.loading=true;
	$http.get('api.php/?artists').success(function(response){
		for(i=0;i<response.data.length;i++)
			$scope.artists.push(response.data[i]);
		});
		$scope.update=function(){
			$rootScope.loading=false;
			$rootScope.hide=false;
			url='api.php/?artist='+$scope.selectedartist;
			console.log(url);
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
			$http.get(url).success( function(response) {
     		$rootScope.data = response.data;
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
		date=new Date();
		url='api.php?markok=' + x + ',' + date;
		console.log(url);
		$http.get(url).success(function(response){
			if(response.success=="true")
				alert("Marked Ok");
			else
				alert("Failure. Error Encountered");
		});
	};

	$scope.markNotOk=function(x){
		date=new Date();
		url='api.php?marknotok=' + x + ',' + date;
		console.log(url);
		$http.get(url).success(function(response){
			if(response.success=="true")
				alert("Marked Not-Ok");
			else
				alert("Failure. Error Encountered");
		});
	};

	$scope.save=function(x){
		date=new Date();
		url='api.php?save=' + JSON.stringify($scope.details) + '|' + date;
		console.log(url);
		$http.get(url).success(function(response){
			if(response.success=='true')
				alert("Saved Successfully");
			else
			console.log(response);
		});
	};
});
