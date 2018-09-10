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
 * A class for implementing a Signature Method
 * See section 9 ("Signing Requests") in the spec
 */
abstract class SignatureMethod {
	/**
	 * Needs to return the name of the Signature Method (ie HMAC-SHA1)
	 * @return string
	 */
	abstract public function getName();

	/**
	 * Build up the signature
	 * NOTE: The output of this function MUST NOT be urlencoded.
	 * the encoding is handled in Request when the final request
	 * is serialized
	 * @param Request $request
	 * @param Consumer $consumer
	 * @param Token $token
	 * @return string
	 */
	abstract public function buildSignature(
		Request $request,
		Consumer $consumer,
		Token $token = null
	);

	/**
	 * Verifies that a given signature is correct
	 * @param Request $request
	 * @param Consumer $consumer
	 * @param Token|null $token
	 * @param string $signature
	 * @return bool
	 */
	public function checkSignature(
		Request $request,
		Consumer $consumer,
		/*Token*/ $token,
		$signature
	) {
		$built = $this->buildSignature( $request, $consumer, $token );

		// Check for zero length, although unlikely here
		if ( strlen( $built ) === 0 || strlen( $signature ) === 0 ) {
			return false;
		}

		if ( strlen( $built ) !== strlen( $signature ) ) {
			return false;
		}

		// Avoid a timing leak with a (hopefully) time insensitive compare
		$result = 0;
		$len = strlen( $signature );
		for ( $i = 0; $i < $len; $i++ ) {
			$result |= ord( $built[$i] ) ^ ord( $signature[$i] );
		}

		return $result == 0;
	}
}
