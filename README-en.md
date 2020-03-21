Version 1.3.2
--------------
Warning. This version requires php and php-curl 7.1 to support "?".
Added caching to reduced load is available here : https://github.com/thetrueavatar/Viessmann-Api/releases/tag/1.3.2
It's also possible to define installationId(3rd line) and gatewayId(4th line) in the credentials.properties.
To get those value please use the getGatewayId and getInstallationid method.
This would reduce the total of request to 3. Moreover authentication(2 request) seems to not be taken into account so it will result in only 1 request counting in the quota.

As mentionned, Viessmann as set 2 limit to their API:
* 120 calls for a time window of 10 minutes
* 1450 calls for a time window of 24 hours

If you wish to contribute or thanks me [![paypal](https://www.paypalobjects.com/fr_FR/BE/i/btn/btn_donate_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3DAXXVZV7PCR6)

Version 1.1.0 available !
-------------------------

Implementation of an API to get data that are expose through the viessmann service.
This service is available through oauth2 autorization and use HATEOAS approach. 
Viessmann is using the specification siren such as defined here:
https://github.com/kevinswiber/siren

The goal of this api is to hide technical aspect to expose only raw data so that users doesn't have to know anything about OAuth2 and Siren. 

This api is implemented in php and is package as a phar(php archive). This phar is available in the release part. 

More explanation cand be found on the wiki https://github.com/thetrueavatar/Viessmann-Api/wiki/english

Full API documentation based on code can be found here [**Viessmann API**](https://htmlpreview.github.io/?https://raw.githubusercontent.com/thetrueavatar/Viessmann-Api/develop/docs/classes/Viessmann.API.ViessmannAPI.html):

Missing Feature ? You can add it by yourself. Have a look at this guide:
[How to add new feature by yourself](https://github.com/thetrueavatar/Viessmann-Api/wiki/How-to-add-you-own-feature-to-the-api):
