# Push Data from XML to Google sheet

## Description

Command-line program, based on the Symfony CLI component. The program
process a local or remote XML file and push the data of that XML file to a Google
Spreadsheet via the Google Sheets API.

## Stack

- PHP 8.2
- Symfony Cli

## Prerequisites

- Docker and Docker Compose
- google console account

## Installation

- create a new application on the google console https://console.cloud.google.com/ and make sure that you enable the 'google sheets api'
- create a service account to this application and then download the credential json file
- copy .env.example to .env file
- put the path of your credential json file on the GOOGLE_AUTH_CONFIG
- create a google sheet file and put the id of this sheet on the GOOGLE_SHEET_ID
- share the sheet with the email of your service account
- docker-compose up --build
- go inside the the container: docker-compose exec app bash
- then run : composer install

## Usage

examples : the header is read by default but if you want disable it so set the header= 0/false
the target node is item by default but if you want change it pass the target node as second paramter ex:"Plant"

'bin/console xml:feed-data "https://www.w3schools.com/xml/plant_catalog.xml" PLANT --no-header'
bin/console xml:feed-data "https://www.w3schools.com/xml/plant_catalog.xml" PLANT --header

## Architecture

### Design Pattern

- singleton for creating one instance from Google Client

- Stratge patterns for diffrent file types: so if you want feed data form different file type other than XML like CV ,Json , easily you can write the implemention in the stratgey folde

- Factory pattern : you don't have to created a new object for each new strategy that you will use

- Data type:
  generator : yeilding the data instead of put all them in one array that will consume the memory specially if the file is too large

also for small files and you just care about time and have no issues with memeory you have array implemention
for exporting 6926 rows and using generator

so to do this go to file config/services and change the autowire from the generator to array
App\Strategy\XmlParserStrategy:
arguments :
$xmlReaderAbstract: '@App\Reader\Xml\XmlArrayReader'

● Have you applied SOLID and/or CLEAN CODE principles?
S : each sing task has it's own service/class (read the data and pushing the data to the sheet)

using normal array.

# Tests

php bin/phpunit

##Questions
I didn't see the point of configure the source type local or remote , since the xml read whatever you sent url or path
but i Implemented anyway as it's required

clear cache
php bin/console cache:clear --env=test
