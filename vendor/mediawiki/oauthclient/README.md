[![Latest Stable Version]](https://packagist.org/packages/mediawiki/oauthclient)
[![License]](https://github.com/wikimedia/mediawiki-oauthclient-php/blob/master/COPYING)
[![Build Status]](https://travis-ci.org/wikimedia/mediawiki-oauthclient-php)

mediawiki/oauthclient
=====================

PHP [OAuth][] client for use with [Wikipedia][] and other MediaWiki-based
wikis running the [OAuth extension][].


Installation
------------

    $ composer require mediawiki/oauthclient


Usage
-----

    <?php
    use MediaWiki\OAuthClient\ClientConfig;
    use MediaWiki\OAuthClient\Consumer;
    use MediaWiki\OAuthClient\Client;

    $endpoint = 'https://localhost/w/index.php?title=Special:OAuth';
    $redir = 'https://localhost/view/Special:OAuth?';
    $consumerKey = 'your key here';
    $consumerSecret = 'your shared secret here';

    $conf = new ClientConfig( $endpoint );
    $conf->setRedirURL( $redir );
    $conf->setConsumer( new Consumer( $consumerKey, $consumerSecret ) );

    $client = new Client( $conf );
    $client->setCallback( 'https://localhost/oauth/callback' );

    // Step 1 = Get a request token
    list( $next, $token ) = $client->initiate();

    // Step 2 - Have the user authorize your app. Get a verifier code from
    // them. (if this was a webapp, you would redirect your user to $next,
    // then use the 'oauth_verifier' GET parameter when the user is redirected
    // back to the callback url you registered.
    echo "Point your browser to: $next\n\n";
    print "Enter the verification code:\n";
    $fh = fopen( 'php://stdin', 'r' );
    $verifyCode = trim( fgets( $fh ) );

    // Step 3 - Exchange the token and verification code for an access
    // token
    $accessToken = $client->complete( $token,  $verifyCode );

    // You're done! You can now identify the user, and/or call the API with
    // $accessToken

    // If we want to authenticate the user
    $ident = $client->identify( $accessToken );
    echo "Authenticated user {$ident->username}\n";

    // Do a simple API call
    echo "Getting user info: ";
    echo $client->makeOAuthCall(
        $accessToken,
        'https://localhost/wiki/api.php?action=query&meta=userinfo&uiprop=rights&format=json'
    );

    // Make an Edit
    $editToken = json_decode( $client->makeOAuthCall(
        $accessToken,
        'https://localhost/wiki/api.php?action=tokens&format=json'
    ) )->tokens->edittoken;

    $apiParams = array(
        'action' => 'edit',
        'title' => 'Talk:Main_Page',
        'section' => 'new',
        'summary' => 'Hello World',
        'text' => 'Hi',
        'token' => $editToken,
        'format' => 'json',
    );

    $client->setExtraParams( $apiParams ); // sign these too

    echo $client->makeOAuthCall(
        $accessToken,
        'https://localhost/wiki/api.php',
        true,
        $apiParams
    );



Running tests
-------------

    composer install --prefer-dist
    composer test


History
-------
The code is a refactored version of [Stype/mwoauth-php][], which in turn is
partially based on [Andy Smith's OAuth library][]. Some code is taken from
[wikimedia/slimapp][].


---
[OAuth]: https://en.wikipedia.org/wiki/OAuth
[Wikipedia]: https://www.wikipedia.org
[OAuth extension]: https://www.mediawiki.org/wiki/Extension:OAuth
[Stype/mwoauth-php]: https://github.com/Stype/mwoauth-php
[Andy Smith's OAuth library]: https://code.google.com/p/oauth/
[wikimedia/slimapp]: https://github.com/wikimedia/wikimedia-slimapp
[Latest Stable Version]: https://img.shields.io/packagist/v/mediawiki/oauthclient.svg?style=flat
[License]: https://img.shields.io/packagist/l/mediawiki/oauthclient.svg?style=flat
[Build Status]: https://img.shields.io/travis/wikimedia/mediawiki-oauthclient-php.svg?style=flat
