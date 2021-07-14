Version 1.4.0
-------------
Basculement sur la version v2 du service Oauth Viessmann. Plusieurs autres modifications voir release note https://github.com/thetrueavatar/Viessmann-Api/releases/tag/1.4.0

Version 1.3.4
-------------
Suppression de la dépendance sur php 7.1 et fix de GetAvailableFeatures
https://github.com/thetrueavatar/Viessmann-Api/releases/tag/1.3.4

Version 1.3.3
--------------

Ajout d'un import DateTime manquant créant une erreur lors du traitement du message de ban de Viessmann:
https://github.com/thetrueavatar/Viessmann-Api/releases/tag/1.3.3

Version 1.3.2
--------------
Attention, cette version nécessite php et php-curl 7.1 pour supporter l'utilisation du "?".
Translated documentation can be found here:
- English: https://github.com/thetrueavatar/Viessmann-Api/blob/develop/README-en.md 

Ajout d'une cache et refactoring pour réduire la charge sur le serveur Viessmann https://github.com/thetrueavatar/Viessmann-Api/releases/tag/1.3.2
Il est désormais possible de définir dans le credentials.properties son installationId(3ème ligne) et son gatewayid(4ème ligne) ce qui réduit le nombre de requêtes nécessaire.
Ces valeurs peuvent être obtenues en appelant les méthodes getGatewayId and getInstallationId avec juste le user/pwd dans credentials.properties.
Cela réduira le nombre de requête à 3 dont 2 pour l'authentification qui ne comptent pas dans le quota.
La cache est utilisée pour tout appelle sur l'objet ViessmannApi.
Le code suivant ne fait donc qu'un seul appel au total:

    <?php
    include __DIR__ . '/bootstrap.php';
    $viessmannApi->getOutsideTemperature());
    $viessmannApi->getBoilerTemperature());
    $viessmannApi->getSlope());
    $viessmannApi->getShift());

Comme déjà expliqué Viessmann limite désormais le nombre de requête sur son service:
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