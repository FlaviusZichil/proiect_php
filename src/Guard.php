<?php

namespace Framework;

interface Guard
{
    public function handle(array $params = null);
    public function reject();
}