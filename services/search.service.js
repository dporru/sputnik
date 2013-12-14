app.factory('Search', function($http){
	var exports = {
		getSpots: function(query, filterType, subcats, callback){
			var params = {
				q:query,
				subcats: angular.toJson(subcats)
			};
			
			if (typeof(filterType) !== 'undefined')
			{
				params.filterType = filterType;
			}
			
			$http({method: 'GET', url: 'search.php', params: params}).
				success(function(data, status, headers, config) {
					var spots = [];
					
					for (var i in data.hits.hits)
					{
						var hit = data.hits.hits[i];
						var spot = hit._source;
						
						var spotDate = new Date(spot.stamp);
						
						spot.dateDiff = new Date().getTime()- spotDate.getTime();
						
						spot.id = hit._id;
						
						spots.push(spot);
					}
					callback(spots);
				}).
				error(function(data, status, headers, config){
					console.error('unable to load article list');
				});
		},
	};
	
	return exports;
});