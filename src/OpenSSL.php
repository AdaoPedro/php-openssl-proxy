<?php 

    /*
    * This file is part of the php-openssl-proxy.
    *
    * (c) Adão Pedro <adao.pedro16@gmail.com>
    *
    * For the full copyright and license information, please view the LICENSE
    * file that was distributed with this source code.
    */

    namespace AdaoPedro\OpenSSLProxy;

    use \Exception;
    use \OpenSSLCertificate;
    use \OpenSSLAsymmetricKey;
    use \OpenSSLCertificateSigningRequest;

    /**
     * Generates a new public and private key.
     */
    function generateNewPKey(?array $configArgs = null): OpenSSLAsymmetricKey|null {
        $privateKey = \openssl_pkey_new($configArgs);
        if(!$privateKey){ return null; }
        return $privateKey;
    }

    /**
     * Generates a CSR
     */
    function generateNewCertificateSigningRequest(
        array $dn, OpenSSLAsymmetricKey $pKey, ?array $configArgs = null, ?array $extraAttribs = null,
    ): OpenSSLCertificateSigningRequest|null {
        $csr = \openssl_csr_new($dn, $pKey, $configArgs, $extraAttribs);
        if(!$csr) { return null; }
        return $csr;
    }

    /**
     * Sign a CSR with itself and generate a certificate
     */
    function generateSelfSignedCertificate(
        OpenSSLCertificateSigningRequest|string $csr, 
        OpenSSLAsymmetricKey $pKey, 
        int $days = 365,
        ?array $configArgs = null,
        int $serial = 0,
    ): OpenSSLCertificate|null {
        $ssCert = \openssl_csr_sign($csr, null, $pKey, $days, $configArgs, $serial);
        if(!$ssCert){ return null; } 
        return $ssCert;
    }

    /**
     * Sign a CSR with another certificate and generate a certificate
     */
    function generateCertificationAuthoritySignedCertificate(
        OpenSSLCertificateSigningRequest|string $csr, 
        OpenSSLCertificate|string $rootCertificate,
        OpenSSLAsymmetricKey|string $rootCertificatePrivateKey, 
        int $days = 365,
        ?array $configArgs = null,
        int $serial = 0,
    ): OpenSSLCertificate|null {
        $casCert = \openssl_csr_sign(
            $csr, $rootCertificate, $rootCertificatePrivateKey, $days, $configArgs, $serial
        );
        if(!$casCert) { return null; }
        return $casCert;
    }

    /**
     * Exports a certificate as a string
     * Note: x509 certificate
     */
    function exportX509From(
        OpenSSLCertificate|string $certificate, ?bool $noText = true
    ): string|null {
        $wasExported = \openssl_x509_export($certificate, $output, $noText);
        if((bool) $wasExported === false  or !$output) {
            throw new Exception("Cannot export x509 certificate as string");
            return null;
        }
        return $output;
    }

    /**
     * Gets an exportable representation of a key into a string
     */
    function exportKeysFrom(
        OpenSSLAsymmetricKey $pKey,
        ?string $passphrase = null,
        ?array $configArgs = null,
    ): array|null {
        $wasExported = \openssl_pkey_export(
            $pKey, $privateKey, $passphrase, $configArgs
        );
        if((bool)$wasExported === false) { return null; }
        $pKeyDetails = \openssl_pkey_get_details($pKey);
        $publicKey = $pKeyDetails["key"];
        return array($privateKey, $publicKey,);
    }

    /**
     * Verify signature
     */
    function verifySignatureOf(
        string $data,
        string $signature,
        string $publicKey,
        string|int $signatureAlgorithm = OPENSSL_ALGO_SHA1
    ): bool|null {
        $result = \openssl_verify(
                    $data,
                    \fromBase64($signature),
                    $publicKey,
                    $signatureAlgorithm,
                );
        if($result === 1) { return true; }
        elseif($result === 0) { return false; }
        else { return null; }
    }

    /**
     * Generate (a base64) signature
     */
    function getSignatureFrom(
        string $data,
        OpenSSLAsymmetricKey|string $privateKey,
        string|int $signatureAlgorithm = OPENSSL_ALGO_SHA1
    ): string|null {
        \openssl_sign(
            $data,
            $signature,
            $privateKey,
            $signatureAlgorithm,
        );

        if(!$signature) { return null; }
        return \toBase64($signature);
    }

    /**
     * Get a private key
     */
    function decryptPrivateKey(
        OpenSSLAsymmetricKey|string $privateKey, string $passPhrase
    ): OpenSSLAsymmetricKey|string|null {
        //Alias: openssl_get_privatekey()
        $result = \openssl_pkey_get_private($privateKey,$passPhrase, );

        if(!$result) { return null; }
        return $result; 
    }

    /**
     * Checks if a private key corresponds to a certificate
     */
    function checkIfPrivateKey(OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $privateKey): object {
        return (new class ($privateKey) {

            public function __construct(
                private OpenSSLAsymmetricKey|OpenSSLCertificate|array|string $privateKey
            ){}

            public function correspondsTo(OpenSSLCertificate|string $certificate): bool {
                return \openssl_x509_check_private_key(
                    $certificate, $this->privateKey, 
                );
            }
        });
    }

    

    

    
