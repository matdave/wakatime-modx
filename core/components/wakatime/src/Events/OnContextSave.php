<?php

namespace WakaTime\Events;

use MatDave\MODXPackage\Elements\Event\Event;
use MODX\Revolution\modContext;

class OnContextSave extends Event
{
    public function run()
    {
        /**
         * @var modContext $obj
         */
        $obj = $this->getOption('context');
        $this->service->createHeartbeat('a=context/update&key=' . $obj->key, true);
    }
}