<?php
/**
 * @section LICENSE
 * Copyright (c) 2007 Andy Smith
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including without
 * limitation the rights to use, copy, modify, merge, publish, distribute,
 * sublicense, and/or sell copies of the Software, and to permit persons to
 * whom the Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 *
 * @file
 */

namespace MediaWiki\OAuthClient;

/**
 * Data type that represents an End User via either and access or requst
 * token.
 */
class Token {
	/**
	 * @var string $key
	 */
	public $key;

	/**
	 * @var string $secret
	 */
	public $secret;

	/**
	 * @param string key The token
	 * @param string secret The token secret
	 */
	function __construct( $key, $secret ) {
		$this->key = $key;
		$this->secret = $secret;
	}

	/**
	 * Generate the basic string serialization of a token that a server
	 * would respond to request_token and access_token calls with
	 *
	 * @return string
	 */
	function toString() {
		return 'oauth_token=' . Util::urlencode( $this->key ) .
			'&oauth_token_secret=' . Util::urlencode( $this->secret );
	}

	function __toString() {
		return $this->toString();
	}
}
