Version 2.0.0 integrate with IOT services
--------------------------------------------------
Viessman has discontinued the access to the old api.

Please note, that somme endpoint/feature may be missing on the new api. Viessmann is adding it on demand. 
Be aware that with  the iot api, you need to generate an api key on https://developer.viessmann.com/ and add it into the clintId field of credentials.properties. 
You'll have to download the new [bootstrap.php](https://github.com/thetrueavatar/Viessmann-Api/raw/features/iot/example/bootstrap.php) and [credentias.properties](https://github.com/thetrueavatar/Viessmann-Api/raw/features/iot/example/credentials.properties).

The Release is available here https://github.com/thetrueavatar/Viessmann-Api/releases/tag/2.0.0

Changelog is now available here: https://github.com/thetrueavatar/Viessmann-Api/blob/develop/Changelog-en.md


If you wish to contribute or thanks me /Si souhaitez me soutenir ou me remercier:[![paypal](https://www.paypalobjects.com/fr_FR/BE/i/btn/btn_donate_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=LPAMB5QUEPV48)



Implementation of an API to get data that are expose through the viessmann service.

The goal of this api is to hide technical aspect to expose only raw data so that users doesn't have to know anything about OAuth2 and Siren. 

This api is implemented in php and is package as a phar(php archive). This phar is available in the release part. 

More explanation cand be found on the wiki https://github.com/thetrueavatar/Viessmann-Api/wiki/english

Full API documentation based on code can be found here [**Viessmann API**](https://htmlpreview.github.io/?https://raw.githubusercontent.com/thetrueavatar/Viessmann-Api/develop/docs/classes/Viessmann.API.ViessmannAPI.html):

Missing Feature ? You can add it by yourself. Have a look at this guide:
[How to add new feature by yourself](https://github.com/thetrueavatar/Viessmann-Api/wiki/How-to-add-you-own-feature-to-the-api):
