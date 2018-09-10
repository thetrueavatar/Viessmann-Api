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

namespace MediaWiki\OAuthClient\SignatureMethod;

use MediaWiki\OAuthClient\Consumer;
use MediaWiki\OAuthClient\Request;
use MediaWiki\OAuthClient\SignatureMethod;
use MediaWiki\OAuthClient\Token;
use MediaWiki\OAuthClient\Util;

/**
 * The RSA-SHA1 signature method uses the RSASSA-PKCS1-v1_5 signature
 * algorithm as defined in [RFC3447] section 8.2 (more simply known as
 * PKCS#1), using SHA-1 as the hash function for EMSA-PKCS1-v1_5. It is
 * assumed that the Consumer has provided its RSA public key in a verified way
 * to the Service Provider, in a manner which is beyond the scope of this
 * specification.
 *   - Chapter 9.3 ("RSA-SHA1")
 */
abstract class RsaSha1 extends SignatureMethod {
	public function getName() {
		return "RSA-SHA1";
	}

	// Up to the SP to implement this lookup of keys. Possible ideas are:
	// (1) do a lookup in a table of trusted certs keyed off of consumer
	// (2) fetch via http using a url provided by the requester
	// (3) some sort of specific discovery code based on request
	// Either way should return a string representation of the certificate
	abstract protected function fetchPublicCert( Request $request );

	// Up to the SP to implement this lookup of keys. Possible ideas are:
	// (1) do a lookup in a table of trusted certs keyed off of consumer
	// Either way should return a string representation of the certificate
	abstract protected function fetchPrivateCert( Request $request );

	public function buildSignature(
		Request $request,
		Consumer $consumer,
		Token $token = null
	) {
		$base_string = $request->getSignatureBaseString();
		$request->base_string = $base_string;

		// Fetch the private key cert based on the request
		$cert = $this->fetchPrivateCert( $request );

		// Pull the private key ID from the certificate
		$privatekeyid = openssl_get_privatekey( $cert );

		// Sign using the key
		$ok = openssl_sign( $base_string, $signature, $privatekeyid );

		// Release the key resource
		openssl_free_key( $privatekeyid );

		return base64_encode( $signature );
	}

	public function checkSignature(
		Request $request,
		Consumer $consumer,
		/*Token*/ $token,
		$signature
	) {
		$decoded_sig = base64_decode( $signature );

		$base_string = $request->getSignatureBaseString();

		// Fetch the public key cert based on the request
		$cert = $this->fetchPublicCert( $request );

		// Pull the public key ID from the certificate
		$publickeyid = openssl_get_publickey( $cert );

		// Check the computed signature against the one passed in the query
		$ok = openssl_verify( $base_string, $decoded_sig, $publickeyid );

		// Release the key resource
		openssl_free_key( $publickeyid );

		return $ok == 1;
	}
}
