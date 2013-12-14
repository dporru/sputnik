 app.filter('millisecondsToStr', function() {
	return function millisecondsToStr (milliseconds) {
		function numberEnding (number) {
			return (number > 1) ? 's, ' : ', ';
		}
		
		var returnString = '';
		var temp = milliseconds / 1000;
		
		var years = Math.floor(temp / 31536000);
		if (years) {
			returnString += years + ' year' + numberEnding(years);
			temp -= years * 31536000;
		}
		var days = Math.floor((temp = temp % 31536000) / 86400);
		if (days) {
			returnString += days + ' day' + numberEnding(days);
			temp -= days * 86400;
		}
		
		if (years && days)
		{
			return returnString.substr(0, returnString.length - 2);
		}
		
		var hours = Math.floor((temp = temp % 86400) / 3600);
		if (hours) {
			returnString += hours + ' hour' + numberEnding(hours);
			temp -= hours * 3600;
		}
		
		if (days && hours)
		{
			return returnString.substr(0, returnString.length - 2);
		}
		
		var minutes = Math.floor((temp = temp % 3600) / 60);
		if (minutes) {
			returnString += minutes + ' minute' + numberEnding(minutes);
			temp -= minutes * 60;
		}
		
		if (hours && minutes)
		{
			return returnString.substr(0, returnString.length - 2);
		}
		
		var seconds = Math.ceil(temp % 60);
		if (seconds) {
			returnString += seconds + ' second' + numberEnding(seconds);
		}
		
		if (returnString == '')
		{
			return 'just now';
		}
		else
		{
			return returnString.substr(0, returnString.length - 2);
		}
	}
});