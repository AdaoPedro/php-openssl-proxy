<?php 

    /*
    * This file is part of the php-openssl-proxy.
    *
    * (c) Adão Pedro <adao.pedro16@gmail.com>
    *
    * For the full copyright and license information, please view the LICENSE
    * file that was distributed with this source code.
    */

    return [
        #Digest method or signature hash, usually one of PHP openssl_get_md_methods() function
        "digest_alg" => "sha1",

        #Selects which extensions should be used when creating an x509 certificate
        "x509_extensions" => "v3_ca",
        
        #Selects which extensions should be used when creating a CSR
        // "req_extensions" => ....
        
        #Specifies how many bits should be used to generate a private key
        "private_key_bits" => 1024,

        #Specifies the type of private key to create. 
        #This can be one of OPENSSL_KEYTYPE_DSA, OPENSSL_KEYTYPE_DH,
        #OPENSSL_KEYTYPE_RSA or OPENSSL_KEYTYPE_EC. 
        #The default value is OPENSSL_KEYTYPE_RSA. 
        "private_key_type" => OPENSSL_KEYTYPE_RSA,

        #The curve_name option was added to make it possible to 
        #create EC keys (OPENSSL_KEYTYPE_EC as private_key_type)
        // "ec" => [
        //     "curve_name" => "prime256v1",
        // ],
        
        #Should an exported key (with passphrase) be encrypted?
        "encrypt_key" => true,

        #Should an exported key (with passphrase) be encrypted?
        "encrypt_key_passphrase" => "12345",

        #One of cipher constants (https://www.php.net/manual/en/openssl.ciphers.php)
        "encrypt_key_cipher" => "aes128"
    ];