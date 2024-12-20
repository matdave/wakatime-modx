<?php

namespace WakaTime\Services\Wakatime;

class Heartbeat
{
    private string $entity;

    private string $type;
    private string $category;
    private float $time;
    private string $project;
    private string $branch;

    private string $language;
    private bool $is_write;

    public function __construct($data)
    {
        $this->entity = $data['entity'] ?? '';

        $this->type = $data['type'] ?? '';
        $this->category = $data['category'] ?? '';
        $this->time = $data['time'];
        $this->project = $data['project'] ?? '';
        $this->branch = $data['branch'] ?? '';

        $this->language = $data['language'] ?? '';
        $this->is_write = $data['is_write'] ?? false;
    }

    public function toArray(): array {
        return [
            'entity' => $this->entity,
            'type' => $this->type,
            'category' => $this->category,
            'time' => $this->time,
            'project' => $this->project,
            'branch' => $this->branch,
            'language' => $this->language,
            'is_write' => $this->is_write,
        ];
    }

    public function toJSON(): string
    {
        return json_encode($this->toArray());
    }
}