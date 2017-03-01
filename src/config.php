<?php

return [

    /**
     * OAuth consumer key.
     */
    'oauthConsumerKey' => null,

    /**
     * OAuth consumer secret.
     */
    'oauthConsumerSecret' => null,

    /**
     * The amount of time cache should last
     *
     * @see http://www.php.net/manual/en/dateinterval.construct.php
     */
    'cacheDuration' => 'PT10M',

    /**
     * Whether request to APIs should be cached or not
     */
    'enableCache' => true,
];
