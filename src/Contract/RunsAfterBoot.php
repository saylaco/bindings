<?php

namespace Sayla\Support\Bindings\Contract;

interface RunsAfterBoot
{
    public function booted(callable $bootedCallback);
}
