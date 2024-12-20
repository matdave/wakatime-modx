<?php

namespace WakaTime\Events;

use MatDave\MODXPackage\Elements\Event\Event;
use MODX\Revolution\modX;

class OnDocFormSave extends Event
{
    public function run()
    {
        $id = $this->getOption('id');
        $this->service->createHeartbeat('a=resource/update&id=' . $id, true);
    }
}