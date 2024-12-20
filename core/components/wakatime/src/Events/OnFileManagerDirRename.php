<?php

namespace WakaTime\Events;

use MatDave\MODXPackage\Elements\Event\Event;
use MODX\Revolution\Sources\modMediaSource;

class OnFileManagerDirRename extends Event
{
    public function run()
    {
        /**
         * @var string $obj
         * @var modMediaSource $source
         */
        $obj = $this->getOption('directory');
        $source = $this->getOption('source');
        $this->service->createHeartbeat('a=system/file/edit&dir=' . $obj . '&source=' . $source->id, true);
    }
}