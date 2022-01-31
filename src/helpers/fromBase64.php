<?php
   
   /*
    * This file is part of the php-openssl-proxy.
    *
    * (c) Adão Pedro <adao.pedro16@gmail.com>
    *
    * For the full copyright and license information, please view the LICENSE
    * file that was distributed with this source code.
    */

    /**
     * Decode a data from a base64 to initial state
     */
    function fromBase64(mixed $data): string {
        return \base64_decode($data);
    }