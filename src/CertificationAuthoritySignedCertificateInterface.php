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

    interface CertificationAuthoritySignedCertificateInterface {

        public function __construct(
            int $days = 365,
            ?CertificateInterface $rootCertificate = null
        );

        public function setRootCertificate(
            CertificateInterface $rootCertificate
        ): self|null;

        public function getRootCertificate(
            CertificateInterface $rootCertificate
        ): CertificateInterface|null;

    }