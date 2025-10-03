# Push Data from XML to Google sheet

Command-line program, based on the Symfony CLI component. The program
process a local or remote XML file and push the data of that XML file to a Google
Spreadsheet via the Google Sheets API.

# Stack

PHP 8.2

## Prerequisites

-   Docker and Docker Compose
-   google console account

## Installation

-   create a new application on the google console https://console.cloud.google.com/ and make sure that you enable the 'google sheets api'
-   create a service account to this application and then download the credential json file
-   copy .env.example to .env file
-   put the path of your credential json file on the GOOGLE_AUTH_CONFIG
-   create a google sheet file and put the id of this sheet on the GOOGLE_SHEET_ID
