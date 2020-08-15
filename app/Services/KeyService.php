<?php

namespace App\Services;

use App\Models\Url;
use RandomLib\Factory as RandomLibFactory;

class KeyService
{
    /**
     * @var \App\Models\Url
     */
    protected $url;

    /**
     * KeyService constructor.
     */
    public function __construct()
    {
        $this->url = new Url;
    }

    /**
     * Make sure the random string generated by randomStringGenerator() is
     * truly unique.
     * @codeCoverageIgnore
     *
     * @return string
     */
    public function randomKey()
    {
        $randomKey = $this->randomStringGenerator();

        // If it is already used (not available), find the next available
        // string.
        $generatedRandomKey = $this->url->whereKeyword($randomKey)->first();
        while ($generatedRandomKey) {
            $randomKey = $this->randomStringGenerator();
            $generatedRandomKey = $this->url->whereKeyword($randomKey)->first();
        }

        return $randomKey;
    }

    /**
     * Generate random strings using RandomLib.
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function randomStringGenerator()
    {
        $alphabet = uHub('hash_char');
        $length = uHub('hash_length');

        $factory = new RandomLibFactory();

        return $factory->getMediumStrengthGenerator()->generateString($length, $alphabet);
    }

    /**
     * Counts the maximum number of random strings that can be generated by a
     * random string generator.
     *
     * @return int
     */
    public function keyCapacity()
    {
        $alphabet = strlen(uHub('hash_char'));
        $length = uHub('hash_length');

        // for testing purposes only
        // tests\Unit\Middleware\UrlHubLinkCheckerTest.php
        if ($length == 0) {
            return 0;
        }

        return pow($alphabet, $length);
    }

    /**
     * Count the remaining random strings that can still be generated by a
     * random string generator.
     *
     * @return int
     */
    public function keyRemaining()
    {
        $keyCapacity = $this->keyCapacity();
        $numberOfUsedKey = $this->numberOfUsedKey();

        return max($keyCapacity - $numberOfUsedKey, 0);
    }

    public function keyRemainingInPercent()
    {
        $capacity = $this->keyCapacity();
        $used = $this->numberOfUsedKey();
        $remaining = $this->keyRemaining();

        $result = round(($remaining / $capacity) * 100, 2);

        if (($result == 0) && ($capacity <= $used)) {
            return '0%';
        } elseif (($result == 0) && ($capacity > $used)) {
            return '0.01%';
        } elseif (($result == 100) && ($capacity != $remaining)) {
            return '99.99%';
        }

        return $result.'%';
    }

    /**
     * Number of unique keys used as short url keys. Calculations performed by
     * the sum total of random string generated by the random string generator
     * plus total custom key that has characteristics similar to the random
     * string generated by the random string generator.
     */
    public function numberOfUsedKey()
    {
        $hashLength = uHub('hash_length');
        $regexPattern = '['.uHub('hash_char').']{'.$hashLength.'}';

        $randomKey = $this->url->whereIsCustom(false)
            ->whereRaw('LENGTH(keyword) = ?', [$hashLength])
            ->count();

        $customKey = $this->url->whereIsCustom(true)
            ->whereRaw('LENGTH(keyword) = ?', [$hashLength])
            ->whereRaw("keyword REGEXP '".$regexPattern."'")
            ->count();

        return $randomKey + $customKey;
    }
}
