Attention:
----------
Depuis quelques jours, Viessmann a activé une protection de son serveur pour éviter que des outils non-autorisés n'accèdent à leur api trop souvent. Il est probable que le code ici ne fonctionne plus chez bon nombre d'entre vous.  
Je ne ferai aucun modification permettent de contourner cela car il est dans le droit de viessmann de contrôler qui peut accéder à leur serveur.
Cette api-ci a toujours été un projet personnel officieux sans support officiel de Viessmann.
Toutefois, Viessmann a indiqué sur son site qu'il limitait juste le nombre d'appel
Voici ce qu'ils indiquent:

120 calls for a time window of 10 minutes
1450 calls for a time window of 24 hours

Par contre, la limite semble un peu buggée et le ban spécialement long(24h). Il est préférable pour le moment de désactiver son cron sous peine d'avoir le ViCare bloqué pendant 24h
> I’m happy to provide you and all other user more concrete information on how the current restriction works:
We have a rate limit with sliding window. Whenever the first request arrives, we open a time window and count all request in that window. If the number of requests reach the limitation, we block all incoming user request until the time window ends. Then, with the next user request, a new time window opens.
Currently, we have two limits active:

120 calls for a time window of 10 minutes
1450 calls for a time window of 24 hours

We see these limitations reasonable, also based on your great explanation concerning cloud based services. So thank you for that!
Also, we decided against HATEOAS as it is deprecated and will sooner or later be switched off.

Toutes les informations sont disponibles sur le fil de discussion suivant chez Viessmann:
https://www.viessmann-community.com/t5/Experten-fragen/Q-amp-A-Viessmann-API/qaq-p/127660/comment-id/117597#M117597

Warning:
----------
Since a few days, Viessmann as set a protection on their server to avoid unofficial third-party tools to overload their api . It's more than likely that the code here won't work for most of you. I won't do any modification to avoid this since it's their right to control the access to their service.
This api as always been a personal project I have shared but has nevre received any official consent/support from Viessman.
According to Viessmann site, is defined such as:
120 calls for a time window of 10 minutes
1450 calls for a time window of 24 hours
HOWEVER, currently the treshold seems to be buggy so please be carreful since the ban last 24h?

> I’m happy to provide you and all other user more concrete information on how the current restriction works:
We have a rate limit with sliding window. Whenever the first request arrives, we open a time window and count all request in that window. If the number of requests reach the limitation, we block all incoming user request until the time window ends. Then, with the next user request, a new time window opens.
Currently, we have two limits active:
120 calls for a time window of 10 minutes
1450 calls for a time window of 24 hours
We see these limitations reasonable, also based on your great explanation concerning cloud based services. So thank you for that!
Also, we decided against HATEOAS as it is deprecated and will sooner or later be switched off.
All information are available on this feed:
https://www.viessmann-community.com/t5/Experten-fragen/Q-amp-A-Viessmann-API/qaq-p/127660/comment-id/117597#M117597

Here is the official communication you'll receive

>_Dear
>{{ insert first_name 'default=default' }} {{ insert last_name 'default=default' }},
>
>an
>evaluation has shown that you have used the web interface to Viessmann IoT Services very frequently in recent days.
>
>On
>the one hand, we are delighted to know you are taking a closer look at our products and solutions. Especially since you have >(apparently) found a solution for your specific use case without a description or our support from our side.
>On
>the other hand, it challenges us to check and channel the method and frequency of requests to our IoT Services in order to >keep those stable and available for all our users.
>
>That’s
>why we have now taken the decision to limit access to our API. From the coming week on, a threshold will become active that >prevents from unauthorized use of the API.
>This will affect all third-party solutions, which can thereby no longer be used.
>
>We
>are aware that the demand for APIs to our technologies for integration into other solutions and third-party systems is >increasing. To provide you with an opportunity for this, we will launch a portal
>in the next two
>months for all developers to get access
>to our API. With the Viessmann Developer Portal, we intend to open ourselves up, release a documentation of the interface >and co-develop new solutions together with you. In addition, we are planning to provide you with useful information around
>our heating systems you need for your development and to offer you a first-level support concerning our APIs.
>
>As one of the users who is already working with our API, we would like to invite you to an early access to the Developer >Portal. If you are interested, please fill out this
>short form. The access to the pre-version of the Portal will be sent to you within the next weeks.
>
>We
>hope you understand the decision that we have to take. If you are interested to support us, on further development on the >API and to explore new possibilities, we are very happy to welcome you to our Developer Portal soon!
>
>In
>the meantime in case you have any questions, feel free to contact us under developer@viessmann.com.
>
>Thank
>you for your understanding.
>
>All
>the best!
>
>Your
>Viessmann Developer Portal Team_

News
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
