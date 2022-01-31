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

    class SSCertificate
        extends Certificate
        implements SaveCertificateInterface, SelfSignedCertificateInterface
    {

        use ParsableCertificate;

        public function __construct(
            int $days = 365,
            string $configFilename =  __DIR__ . "/../openssl_config.php"
        ) {
            parent::__construct($configFilename);
            $this->days = $days;
        }

        public function save(): self|null {
            try {
                /**
                 * Pair key
                 */
                $pKey = generateNewPKey($this->settings);
                if(!$pKey){
                    throw new Exception("Error. it was not possible to generate the pKey");
                    return null;
                }
                if($this->dn === []){ 
                    throw new Exception("Error. DistinguishNames are not defined"); 
                    return null;
                }
                $this->csr = generateNewCertificateSigningRequest($this->dn, $pKey, $this->settings);

                if (!$this->csr) {
                    throw new Exception("Error. An error occurred when trying to create the certificate signing request");
                    return null;
                }

                $this->certificate = generateSelfSignedCertificate($this->csr, $pKey, $this->days, $this->settings);
                
                if (!$this->certificate) {
                    throw new Exception("rror: It was not possible to create the certificate");
                    return null;
                }
            } catch(Exception $ex) {
                throw new Exception($ex->getMessage());
                return null;
            }

            if (!$this->certificate) {
                throw new Exception("Error. An error occurred when trying to save the certificate");
                return null;
            }

            $this->x509 = exportX509From($this->certificate);
            if(!$this->x509){
                throw new Exception("Error. it was not possible to export the x509 certificate");
                return null;
            }

            list($this->privateKey, $this->publicKey) = exportKeysFrom(
                $pKey,
                $this->isEncrypted() === true
                    ? $this->settings["encrypt_key_passphrase"]
                    : null,
                $this->settings
            );
            if(!$this->privateKey or !$this->publicKey) {
                throw new Exception("Error. it was not possible to export the public and private keys");
                return null;
            }
            
            $this->isSaved = true;
            return $this;
        }

    }