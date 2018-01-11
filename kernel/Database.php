<?php

class Database
{
    private $path;

    public function __construct()
    {
        $this->path = __DIR__ . '/data';
        if (!file_exists("{$this->path}")) @mkdir("{$this->path}", 0740, true);
    }

    public function insert($key, $value)
    {
        $path = $this->path . '/' . substr($key, 0, 6);
        if (!file_exists("{$path}")) @mkdir("{$path}", 0740, true);
        return file_put_contents("{$path}/{$key}.log", serialize($value));
    }

    public function get($key)
    {
        $path = $this->path . '/' . substr($key, 0, 6);
        $data = file_get_contents("{$path}/{$key}.log");
        if (!$data) return null;
        return unserialize($data);
    }

}