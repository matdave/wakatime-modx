<?php

$wt = new WakaTime\Service($modx);

$class = "WakaTime\\Events\\{$modx->event->name}";

if (class_exists($class)) {
    $event = new $class($wt, $scriptProperties ?? []);
    $event->run();
}