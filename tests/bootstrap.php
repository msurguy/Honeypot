<?php

namespace {
    require_once __DIR__ . '/../vendor/autoload.php';
}

namespace Msurguy\Honeypot {

    /**
     * Stub the time() function for tests.
     *
     * @return int
     */
    function time()
    {
        return 1000;
    }

}
