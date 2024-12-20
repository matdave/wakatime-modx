<?php

namespace WakaTime\Events;

use MatDave\MODXPackage\Elements\Event\Event;

class OnManagerPageInit extends Event
{
    public function run()
    {
        $context = 'a=' . $this->getOption('action');
        $lang = 'html';
        if (str_contains($context, 'snippet') ||
            str_contains($context, 'plugin')) {
            $lang = 'php';
        }
        if (isset($_REQUEST['id']) &&
            is_numeric($_REQUEST['id']) &&
            $_REQUEST['id'] > 0) {
            $context .= '&id=' . $_REQUEST['id'];
        }
        if (isset($_REQUEST['namespace'])) {
            $context .= '&namespace=' . $_REQUEST['namespace'];
        }
        if (isset($_REQUEST['file'])) {
            $context .= '&file=' . $_REQUEST['file'];
        }
        if (isset($_REQUEST['source'])) {
            $context .= '&source=' . $_REQUEST['source'];
        }
        if (isset($_REQUEST['key'])) {
            $context .= '&key=' . $_REQUEST['key'];
        }
        $this->service->createHeartbeat($context, false, $lang);
    }
}