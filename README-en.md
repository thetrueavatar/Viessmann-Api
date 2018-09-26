Implementation of an API to get data that are expose through the viessmann service.
This service is available through oauth2 autorization and use HATEOAS approach. 
Viessmann is using the specification siren such as defined here:
https://github.com/kevinswiber/siren

The goal of this api is to hide technical aspect to expose only raw data so that users doesn't have to know anything about OAuth2 and Siren. 

This api is implemented in php and is package as a phar(php archive). This phar is available in the release part. 

More explanation cand be found on the wiki https://github.com/thetrueavatar/Viessmann-Api/wiki/english