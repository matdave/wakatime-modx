<?php

namespace WakaTime\Events;

use MatDave\MODXPackage\Elements\Event\Event;
use MODX\Revolution\modX;

class OnSnippetFormDelete extends Event
{
    public function run()
    {
        $id = $this->getOption('id');
        $this->service->createHeartbeat('a=element/snippet/update&id=' . $id, true, 'php');
    }
}