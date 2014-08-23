<?php

use Wataridori\Bphalcon\BController;

class ControllerBase extends BController
{
    public function initialize()
    {
        $this->tag->setTitle('BPhalcon');
    }
}
