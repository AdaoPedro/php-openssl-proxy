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

    use \OpenSSLCertificate;
    use \OpenSSLAsymmetricKey;
    use \OpenSSLCertificateSigningRequest;

    interface CertificateInterface {

        public function getExpirationDate(): string|null;

        public function getX509(): string|null;

        public function getPrivateKey(): string|null;

        public function isEncrypted(): bool;

        public function getPrivateKeyDecrypted(): OpenSSLAsymmetricKey|string;

        public function getPublicKey(): string|null;

        public function getCsr(): OpenSSLCertificateSigningRequest|null;

        public function setDistinguishNames(
            string $countryName,
            string $stateOrProvinceName,
            string $localityName,
            string $organizationName,
            string $organizationalUnitName,
            string $commonName,
            string $emailAddress
        ): self;

        public function get(): OpenSSLCertificate|null;

    }