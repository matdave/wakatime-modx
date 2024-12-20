<?php

namespace WakaTime\Events;

use MatDave\MODXPackage\Elements\Event\Event;
use MODX\Revolution\Sources\modMediaSource;

class OnFileManagerFileCreate extends Event
{
    public function run()
    {
        /**
         * @var string $obj
         * @var modMediaSource $source
         */
        $obj = $this->getOption('path');
        $source = $this->getOption('source');
        $this->service->createHeartbeat('a=system/file/edit&file=' . $obj . '&source=' . $source->id, true);
    }
}