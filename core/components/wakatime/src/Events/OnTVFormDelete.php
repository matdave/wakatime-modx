<?php

namespace WakaTime\Events;

use MatDave\MODXPackage\Elements\Event\Event;
use MODX\Revolution\modX;

class OnTVFormDelete extends Event
{
    public function run()
    {
        $id = $this->getOption('id');
        $this->service->createHeartbeat('a=element/tv/update&id=' . $id, true);
    }
}