<?php
// load config
require_once('config.php');
?>
<html ng-app="sputnik" ng-csp>
  <head>
    <title>Sputnik - An ElasticSearch powered Spotweb frontend</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/screen.css" rel="stylesheet">
  </head>
  <body>
	
	<div ng-controller="MainCtrl" class="container">
		<div class="row">
			<div class="col-md-4 appSearch well">
				<form ng-submit="cleanSearch()">
					<div class="form-group">
						<label for="query">Search text</label>
						<input type="text" id="query" ng-model="query" placeholder="Search text" class="form-control" />
					</div>
					
					<div class="form-group">
						<div class="radio">
							<label>
								<input type="radio" name="filterChoice" ng-model="filterChoice" ng-change="filterChoiceChanged()" value="no-filter" />
								No filter
							</label>
						</div>
						<div class="radio">
							<label>
								<input type="radio" name="filterChoice" ng-model="filterChoice" ng-change="filterChoiceChanged()" value="filter" />
								Filters
							</label>
						</div>
						
						<div class="filters" ng-show="showFilters">
							<div class="radio">
								<label>
									<input type="radio" name="filterType" ng-model="filterType" ng-change="filterTypeChanged()" value="0" />
									Video
								</label>
							</div>
							
							<div class="filters" ng-show="showVideoFilters">
								<div class="checkbox" ng-repeat="videoFilter in videoFilters">
									<label>
										<input type="checkbox" ng-model="videoFilter.value" ng-change="filtersChanged()" />
										{{videoFilter.label}}
									</label>
								</div>
							</div>
							
							<div class="radio">
								<label>
									<input type="radio" name="filterType" ng-model="filterType" ng-change="filterTypeChanged()" value="1" />
									Music
								</label>
							</div>
							
							<div class="filters" ng-show="showMusicFilters">
								<div class="checkbox" ng-repeat="musicFilter in musicFilters">
									<label>
										<input type="checkbox" ng-model="musicFilter.value" ng-change="filtersChanged()" />
										{{musicFilter.label}}
									</label>
								</div>
							</div>
							
							<div class="radio">
								<label>
									<input type="radio" name="filterType" ng-model="filterType" ng-change="filterTypeChanged()" value="2" />
									Games
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="filterType" ng-model="filterType" ng-change="filterTypeChanged()" value="3" />
									Software
								</label>
							</div>
						</div>
					</div>
					<input type="submit" value="Search" class="btn btn-success pull-right" />
					<div class="clearfix"></div>
				</form>
			</div>
			<div class="col-md-8 appResults">
				<table class="table table-striped">
					<thead>
						<tr>
							<th class="hidden-xs">Category</th>
							<th>Title</th>
							<th class="hidden-xs">Sender</th>
							<th class="hidden-xs">Age</th>
							<th class="hidden-xs">Filesize</th>
							<th class="visible-xs">Fileinfo</th>
							<th>NZB</th>
						<tr>
					</thead>
					<tbody>
						<tr ng-repeat="spot in spots">
							<td class="hidden-xs cat{{spot.category}}">{{getCatName(spot)}}</td>
							<td>
								<em class="visible-xs">{{spot.poster}}</em>
								<a ng-href="<?=rtrim($spotweb_url,'/')?>/#/spotweb/?page=getspot&messageid={{spot.id}}" target="_blank">
									{{spot.title}}
								</a><br>
								<span class="visible-xs age">Age {{spot.dateDiff | millisecondsToStr}}</span>
							</td>
							<td class="hidden-xs">{{spot.poster}}</td>
							<td class="hidden-xs age">{{spot.dateDiff | millisecondsToStr}}</td>
							<td class="filesize">
								<span class="visible-xs cat{{spot.category}}">{{getCatName(spot)}}</span>
								{{spot.filesize | bytes}}
							</td>
							<td><a ng-href="<?=rtrim($spotweb_url,'/')?>/?page=getnzb&action=display&messageid={{spot.id}}" target="_blank">NZB</a></td>
						</tr>
					</tbody>
				</table>
				<ul class="pager">
					<li ng-class="{disabled: page == 0}"><a ng-click="previousPage()">Previous</a></li>
					<li ng-class="{disabled: page == totalNumberOfPages()}"><a ng-click="nextPage()">Next</a></li>
				</ul>
			</div>
		</div>
	</div>
	
	<script type="text/javascript" src="js/angular.min.js"></script>
	<script type="text/javascript" src="js/angular-route.min.js"></script>
	<script type="text/javascript" src="js/angular-sanitize.min.js"></script>
	<script type="text/javascript" src="app/main.js"></script>
	<script type="text/javascript" src="controllers/mainCtrl.js"></script>
	<script type="text/javascript" src="services/search.service.js"></script>
	<script type="text/javascript" src="filters/bytes.filter.js"></script>
	<script type="text/javascript" src="filters/millisecondsToStr.filter.js"></script>
  </body>
</html>