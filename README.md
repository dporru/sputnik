# Sputnik

An ElasticSearch powered Spotweb frontend.

Sputnik uses Elasticsearch to search through spots, making it lightning fast and enhancing search results dramatically. Sputnik uses the Spotweb database to index spots and refers the user to the Spotweb UI for NZB downloading and the spot detail view.

`Sputnik only provides a means to search quickly and efficiently throught your spot-database. Everything else is handled by Spotweb.`

## Installation

Before you can start installing Sputnik, Spotweb needs to be installed. For installation instructions for Spotweb, see the [Spotweb wiki](https://github.com/spotweb/spotweb/wiki)

When spotweb is installed follow these steps:

1. Install Elasticsearch. Get it from their [download page](http://www.elasticsearch.org/download/). Make sure the server starts at boot. Installing the .deb package on a Debian/Ubuntu system will do that. (Don't forget to block port 9200 on your firewall if you don't want everyone else to use your Elasticsearch server.)
2. Download Sputnik and put it in your www-folder. For Debian/Ubuntu this is /var/www
3. Change any settings in config.php, if you have a non-standard installation.
4. Copy the sputnik_import script to /etc/cron.daily
	$ sudo cp /var/www/sputnik_import /etc/cron.daily/
5. Run the sputnik_import script to start importing spots from the Spotweb database. (This will take a while)
6. Go to http://localhost/sputnik to start searching.

If you have any questions or problems, please let me know!

## To do

* More search results
* More filters (games, software)
* Searchlist that fits on a mobile interface