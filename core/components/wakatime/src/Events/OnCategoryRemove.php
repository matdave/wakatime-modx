<?php

namespace WakaTime\Events;

use MatDave\MODXPackage\Elements\Event\Event;
use MODX\Revolution\modCategory;
use MODX\Revolution\modX;

class OnCategoryRemove extends Event
{
    public function run()
    {
        /**
         * @var modCategory $obj
         */
        $obj = $this->getOption('category');
        $this->service->createHeartbeat('a=category/update&id=' . $obj->category, true);
    }
}