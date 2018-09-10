<?php
/**
 * @section LICENSE
 * This file is part of the MediaWiki OAuth Client library
 *
 * The MediaWiki OAuth Client library is free software: you can
 * redistribute it and/or modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation, either version 3 of
 * the License, or (at your option) any later version.
 *
 * MediaWiki OAuth Client library is distributed in the hope that it
 * will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with the MediaWiki OAuth Client library. If not, see
 * <http://www.gnu.org/licenses/>.
 *
 * @file
 * @copyright © 2015 Chris Steipp, Wikimedia Foundation and contributors.
 */

namespace MediaWiki\OAuthClient;

/**
 * MediaWiki OAuth client configuration
 */
class ClientConfig {
	/**
	 * Url to the OAuth special page
	 * @var string $endpointURL
	 */
	public $endpointURL;

	/**
	 * Canonical server url, used to check /identify's iss.
	 * A default value will be created based on the provided $endpointURL.
	 * @var string $canonicalServerUrl
	 */
	public $canonicalServerUrl;

	/**
	 * Url that the user is sent to. Can be different from $endpointURL to
	 * play nice with MobileFrontend, etc.
	 * @var string|null $redirURL
	 */
	public $redirURL = null;

	/**
	 * Use https when calling the server.
	 * @var bool $useSSL
	 */
	public $useSSL;

	/**
	 * If you're testing against a server with self-signed certificates, you
	 * can turn this off but don't do this in production.
	 * @var bool $verifySSL
	 */
	public $verifySSL;

	/**
	 * @var Consumer|null $consumer
	 */
	public $consumer = null;

	/**
	 * @param string $url OAuth endpoint URL
	 * @param bool $verifySSL
	 */
	function __construct( $url, $verifySSL = true ) {
		$this->endpointURL = $url;
		$this->verifySSL = $verifySSL;

		$parts = parse_url( $url );
		$this->useSSL = $parts['scheme'] === 'https';
		$this->canonicalServerUrl = "{$parts['scheme']}://{$parts['host']}" .
			( isset( $parts['port'] ) ? ':' . $parts['port'] : '' );
	}

	/**
	 * @param string $redirURL
	 * @return ClientConfig Self, for method chaining
	 */
	public function setRedirUrl( $redirURL ) {
		$this->redirURL = $redirURL;
		return $this;
	}

	/**
	 * @param Consumer $consumer
	 * @return ClientConfig Self, for method chaining
	 */
	public function setConsumer( Consumer $consumer ) {
		$this->consumer = $consumer;
		return $this;
	}
}
