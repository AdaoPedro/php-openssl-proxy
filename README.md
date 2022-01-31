# php-openssl-proxy

## About
A PHP wrapper around the OpenSSL extension that provides a user-friendly interface for dealing with OpenSSL.

## What's up with the "proxy" name?
It is simply an analogy of the role of a proxy server - which acts as an intermediary.

## Features
Create X.509, CSRs and CRLs certificates,
Create RSA, HD and DSA keys,
Generate and verify signatures,
Encoding and decoding,
Parsing x509 certificate.

## Requirements
This library needs PHP 8 or greater,
ext-openssl.

## Installation
```bash
composer require adaopedro/php-openssl-proxy
```

## Example Usage

### Creating a Self-Signed Certificate

```php
use AdaoPedro\OpenSSLProxy\SSCertificate;

$ssCertificate = (new SSCertificate(
    days: 365, //expiration
))->setDistinguishNames(
        countryName: "AO",
        stateOrProvinceName: "Angola",
        localityName: "Luanda",
        organizationName: "A Pedro Developers (SU), Lda",
        organizationalUnitName: "AP",
        commonName: "apedrodevelopers",
        emailAddress: "contato@apdev.ao"
);

try {
    $ssCertificate->save();
} catch(\Exception $ex) {
    echo $ex->getMessage() . PHP_EOL;
}
```

### Creating a CA-Signed Certificate

```php
use AdaoPedro\OpenSSLProxy\CASCertificate;

$certificate = (new CASCertificate(
    days: 365, //expiration
    rootCertificate: $rootCertificate, //an instance of a Self-Signed Certificate, for example
))->setDistinguishNames(
        //...
);

try {
    $certificate->save();
} catch(\Exception $ex) {
    echo $ex->getMessage() . PHP_EOL;
    }
```

### Exporting a certificate as a string

```php
//$certificate => an instance of SS or CAS Certificate
echo $certificate->getx509();
```

### Exporting a certificate as an PHP OpenSSLCertificate object
```php
//$certificate => an instance of SS or CAS Certificate
var_dump(
    $certificate->get()
);
```

### Exporting public and private keys from a certificate
```php
 //$certificate => an instance of SS or CAS Certificate
var_dump(
    $certificate->getPublicKey(),
);

//$certificate => an instance of SS or CAS Certificate
var_dump(
    $certificate->getPrivateKey(),
);
```

### Generating public and private keys

```php
$pKey = \AdaoPedro\OpenSSLProxy\generateNewPKey();

list($privKey, $pubKey) = \AdaoPedro\OpenSSLProxy\exportKeysFrom($pKey);

echo $pubKey . PHP_EOL;
echo $privKey . PHP_EOL;
```

### Signing

```php
$data = "Hello world!!";

$signature = \AdaoPedro\OpenSSLProxy\getSignatureFrom(
    $data,
    file_get_contents(".../private_key.pem"),
);
```

### Signature verification

```php
$data = "Hello world!!";

echo
\AdaoPedro\OpenSSLProxy\verifySignatureOf(
    $data,
    file_get_contents(".../hash.dat"),
    file_get_contents(".../public_key.pem"),
) === true
? "Verified"
: "Error. Data modified";
```

### Parsing a PHP OpenSSLCertificate certificate object

```php
//$certificate => an instance of SS or CAS Certificate
var_dump(
    $certificate()
);
```

### Checks if a private key corresponds to a certificate

```php
echo
\AdaoPedro\OpenSSLProxy\checkIfPrivateKey(
    file_get_contents(".../private_key.pem")
)->correspondsTo(
    file_get_contents(".../cert.pem")
) === true
? "Yes. It does"
: "No. It does not";
```

### Customizing OpenSSL configurations (in case when we're using certificate generator classes)

```php
use AdaoPedro\OpenSSLProxy\SSCertificate;

//you can find the initial config file in root of lib directory
/*
To customize, just pass the config filename as second parameter to SSCertificate constructor
or third parameter in case of CASCertificate
*/

$certificate = (new SSCertificate(
    days: 365, //expiration
    configFilename: __DIR__ . "/openssl_configs.php"
))->setDistinguishNames(
        //...
);
```