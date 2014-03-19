function MainCtrl($scope, Search){
	
	$scope.total = 0;
	$scope.page = 0;
	$scope.itemsPerPage = 10;
	
	$scope.filterChoice = 'no-filter';
	$scope.showFilters = false;
	$scope.showVideoFilters = false;
	$scope.showMusicFilters = false;
	
	$scope.videoFilters = [
		{
			label: 'DivX',
			value: false,
			subcats: {
				'subcata' : ['a0']
			}
		},
		{
			label: 'DVD',
			value: false,
			subcats: {
				'subcata' : ['a3','a10']
			}
		},
		{
			label: 'HD',
			value: false,
			subcats: {
				'subcata' : ['a4','a6','a7','a8','a9']
			}
		},
		{
			label: 'Series',
			value: false,
			subcats: {
				'subcatz' : ['z1']
			}
		}
	];
	
	$scope.musicFilters = [
		{
			label: 'Compressed',
			value: false,
			subcats: {
				'subcata' : ['a0','a3','a5','a6']
			}
		},
		{
			label: 'Lossless',
			value: false,
			subcats: {
				'subcata' : ['a4','a4','a7','a8']
			}
		}
	];
	
	$scope.search = function(){
		var subcats = {};
		var filters = $scope.videoFilters.concat($scope.musicFilters);
		
		for (var i in filters){
			var filter = filters[i];
			if (filter.value){
				for (var subcat in filter.subcats){
					if (typeof(subcats[subcat]) === 'undefined'){
						subcats[subcat] = [];
					}
					subcats[subcat] = subcats[subcat].concat(filter.subcats[subcat]);
				}
			}
		}
		
		var startRecord = $scope.page * $scope.itemsPerPage;
		var numberOfRecords = $scope.itemsPerPage;
		
		Search.getSpots($scope.query, $scope.filterType, subcats, startRecord, numberOfRecords, function(data, total){
			$scope.spots = data;
			$scope.total = total;
		});
	};
	
	$scope.search();
	
	$scope.cleanSearch = function()
	{
		$scope.page = 0;
		$scope.search();
	}
	
	$scope.getCatName = function(spot){
		switch(spot.category){
			case '0':
				if (spot.subcata.indexOf('a0') !== -1){
					return 'DivX';
				}
				if (spot.subcata.indexOf('a9') !== -1){
					return 'x264HD';
				}
				if (spot.subcata.indexOf('a1') !== -1){
					return 'WMV';
				}
				if (spot.subcata.indexOf('a2') !== -1){
					return 'MPG';
				}
				if (spot.subcata.indexOf('a3') !== -1){
					return 'DVD5';
				}
				if (spot.subcata.indexOf('a10') !== -1){
					return 'DVD5';
				}
				if (spot.subcata.indexOf('a5') !== -1){
					return 'ePub';
				}
				if (spot.subcata.indexOf('a4') !== -1){
					return 'HD Oth';
				}
				if (spot.subcata.indexOf('a6') !== -1){
					return 'Blu-ray';
				}
				break;
			
			case '1':
				if (spot.subcata.indexOf('a0') !== -1){
					return 'MP3';
				}
				if (spot.subcata.indexOf('a8') !== -1){
					return 'FLAC';
				}
				return 'Music';
				break;
				
			case '2':
				return 'game';
				break;
				
			case '3':
				return 'software';
				break;
		}
	};
	
	$scope.filterChoiceChanged = function()
	{
		$scope.page = 0
		$scope.showFilters = ($scope.filterChoice == 'filter');
		
		if ($scope.filterChoice == 'no-filter')
		{
			delete($scope.filterType);
			$scope.showVideoFilters = false;
			$scope.showMusicFilters = false;
			$scope.search();
		}
	}
	
	$scope.filterTypeChanged = function()
	{
		$scope.page = 0
		$scope.search();
		
		$scope.showVideoFilters = ($scope.filterType == 0);
		$scope.showMusicFilters = ($scope.filterType == 1);
	}
	
	$scope.filtersChanged = function()
	{
		$scope.page = 0
		$scope.search();
	}
	
	$scope.previousPage = function()
	{
		$scope.page = Math.max(0, $scope.page - 1);
		$scope.search();
	}
	
	$scope.nextPage = function()
	{
		$scope.page = Math.min(Math.floor($scope.total/$scope.itemsPerPage), $scope.page + 1);
		$scope.search();
	}
	
	$scope.totalNumberOfPages = function()
	{
		return Math.floor($scope.total/$scope.itemsPerPage);
	}
}