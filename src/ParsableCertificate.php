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

    trait ParsableCertificate {

        public function __invoke(): array|null {
            if(!$this->isSaved) {
                throw new \Exception("Error. You should save the certificate first executing the save() method");
                return null;
            }
            return \openssl_x509_parse(
                $this->certificate,
                short_names: false,
            );
        }

    }