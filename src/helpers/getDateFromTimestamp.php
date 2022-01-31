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
     * Generates a friendly and readable date from a timestamp.
     * Note: ts => Timestamp
     */
    function getDateFromTimestamp(int $ts, string $format = "d-m-Y"): string {
        return date($format, $ts);
    }