Version 1.3.2
--------------
Addded caching to reduced load is available here : https://github.com/thetrueavatar/Viessmann-Api/releases/tag/1.3.2
It's also possible to define installationId(3rd line) and gatewayId(4th line) in the credentials.properties.
To get those value please use the getGatewayId and getInstallationid method.
This would reduce the total of request to 3. Moreover authentication(2 request) seems to not be taken into account so it will result in only 1 request counting in the quota.

As mentionned, Viessmann as set 2 limit to their API:
* 120 calls for a time window of 10 minutes
* 1450 calls for a time window of 24 hours

News FR
----
Une nouvelle version utilisant une cache et évitant un nombre trop important d'appel est disponible en snapshot. Cette version a été développé à l'aveulge(mon compte est bloqué) mais fonctionne en test local. Faites-moi le plus de retour possible ! 
Attention, la cache fonctionne à condition que vous fassiez tout vos appels sur le même objet viessmannApi.
Exemple:

 $viessmannApi->getOutsideTemperature());

 $viessmannApi->getBoilerTemperature());
 
 $viessmannApi->getSlope());
 
 $viessmannApi->getShift());
 `

News EN
----
A new version is available in snaphost that provide a caching to avoid account to be blocked. This version has been developed while I don't have access to my own account so please provide me feedback asap.

This cache only works if you do all the call in the same php file.
Example:

 $viessmannApi->getOutsideTemperature());

 $viessmannApi->getBoilerTemperature());
 
 $viessmannApi->getSlope());
 
 $viessmannApi->getShift());

If you wish to contribute or thanks me /Si souhaitez me soutenir ou me remercier:[![paypal](https://www.paypalobjects.com/fr_FR/BE/i/btn/btn_donate_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3DAXXVZV7PCR6)

Generail info
-----

New Version(previous phar was corrupted) 1.2.1 available here ! : https://github.com/thetrueavatar/Viessmann-Api/releases/tag/1.2.1

Translated documentation can be found here:
- English: https://github.com/thetrueavatar/Viessmann-Api/blob/develop/README-en.md 

Implémentation d'une interface pour récupérer les données exposées par le service Viessmann.

Ce service accessible via autorisation OAUTH2 expose les données via l'approche HATEOAS implémentée par Siren dont la spécification est définie ici:

https://github.com/kevinswiber/siren

Le but de l'api est de cacher ces aspects techniques poru exposer directement les données.

Je suis novice en php(JAVAEE Dev) donc il se peut que je ne connaisse pas les conventions/habitudes php. Tout conseil/remarque est apprécié et n'hésitez pas à contribuer !

Je précise aussi que je partage mon dev perso mais ne souhaite pas faire un support intensif (pas le temps). Je ne donne donc pas de garantie sur la résolution de tel ou tel bug en terme de délais de résolution.
De toute façon, cmme on dit dans l'open-source "Please contribute" ;-)

Pour voir les explications sur l'utilisation voir wiki: https://github.com/thetrueavatar/Viessmann-Api/wiki/French ou le code de example/Main.php

Voici la doc des méthodes de l'api [**Viessmann API**](https://htmlpreview.github.io/?https://raw.githubusercontent.com/thetrueavatar/Viessmann-Api/develop/docs/classes/Viessmann.API.ViessmannAPI.html):

Une fonctionnalité manque ? N'hésitez pas à l'ajouter vous-même ! Je suis en train de construire un petit guide pour le faire:
[How to add new feature by yourself](https://github.com/thetrueavatar/Viessmann-Api/wiki/How-to-add-you-own-feature-to-the-api):
