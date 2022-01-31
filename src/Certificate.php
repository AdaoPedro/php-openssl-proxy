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

    abstract class Certificate implements CertificateInterface {

        protected int $days = 365;
        /**
         * Note: dns => DistinguishNames
         */
        protected array $dn = [];

        /**
         * Note: csr => Certificate Signing Request
         * @var
         */
        protected OpenSSLCertificateSigningRequest|null $csr = null;

        /**
         * Note: x509 certificate
         */
        protected string|null $x509 = null;

        protected OpenSSLCertificate|null $certificate = null;
            
        protected string|null $privateKey = null;

        protected string|null $publicKey = null;

        protected bool $isSaved = false;

        /**
         * Settings to generate certificates and keys
         */
        protected array|null $settings = null;

        public function __construct (string $configFilename){
            $this->settings = require($configFilename);
        }

        public function setDistinguishNames(
            string $countryName,
            string $stateOrProvinceName,
            string $localityName,
            string $organizationName,
            string $organizationalUnitName,
            string $commonName,
            string $emailAddress
        ): self {
            $this->dn = [
                "countryName" => $countryName,
                "stateOrProvinceName" => $stateOrProvinceName,
                "localityName" => $localityName,
                "organizationName" => $organizationName,
                "organizationalUnitName" => $organizationalUnitName,
                "commonName" => $commonName,
                "emailAddress" => $emailAddress,
            ];
            return $this;
        }

        public function setRootCertificate(
            CertificateInterface $rootCertificate
        ): self|null {
            $this->rootCertificate = $rootCertificate;
            return $this;
        }

        public function getRootCertificate(
            CertificateInterface $rootCertificate
        ): CertificateInterface|null {
            if(!$this->isSaved) {
                throw new Exception("Error. You should save the certificate first executing the save() method");
                return null;
            }
            return $this->rootCertificate;
        }

        public function getExpirationDate(): string|null {
            if(!$this->isSaved) {
                throw new Exception("Error. You should save the certificate first executing the save() method");
                return null;
            }
            return getDateFromTimestamp(
                strtotime("+ {$this->days}day"),
            );
        }

        public function isEncrypted(): bool {
            return (
                \array_key_exists("encrypt_key", $this->settings)
                and $this->settings["encrypt_key"] === true
            ) ? true : false;
        }

        public function getX509(): string|null {
            if(!$this->isSaved) {
                throw new Exception("Error. You should save the certificate first executing the save() method");
                return null;
            }
            return $this->x509;
        }

        public function getPrivateKey(): string|null {
            if(!$this->isSaved) {
                throw new Exception("Error. You should save the certificate first executing the save() method");
                return null;
            }
            return $this->privateKey;
        }

        public function getPrivateKeyDecrypted(): OpenSSLAsymmetricKey|string {
            if(!$this->isEncrypted()){
                return $this->getPrivateKey();
            }
            return decryptPrivateKey(
                $this->getPrivateKey(),
                $this->settings["encrypt_key_passphrase"],
            );
        }

        public function getPublicKey(): string|null {
            if(!$this->isSaved) {
                throw new Exception("Error. You should save the certificate first executing the save() method");
                return null;
            }
            return $this->publicKey;
        }

        public function getCsr(): OpenSSLCertificateSigningRequest|null {
            if(!$this->isSaved) {
                throw new Exception("Error. You should save the certificate first executing the save() method");
                return null;
            }
            return $this->csr;
        }

        public function get(): OpenSSLCertificate|null {
            if(!$this->isSaved) {
                throw new Exception("Error. You should save the certificate first executing the save() method");
                return null;
            }
            return $this->certificate;
        }

    }