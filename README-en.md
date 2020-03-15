Warning:
----------
Since a few days, Viessmann as set a protection on their server to avoid unofficial third-party tools to use their api. It's more than likely that the code here won't work for most of you. I won't do any modification to avoid this since it's their right to control the access to their service.
This api as always been a personal project I have shared but has nevre received any official consent/support from Viessman.


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

Version 1.1.0 available !


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
