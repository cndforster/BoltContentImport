<?php

namespace Topolis\Bolt\Extension\ContentImport;

interface IFormat {

    /**
     * @param string $url
     * @return array
     */
    public function parse($url);

}
