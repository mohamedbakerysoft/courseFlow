<?php

return [
    'slow_mode' => (bool) (env('DUSK_SLOW_MODE', false)),
    'slow_ms' => (int) (env('DUSK_SLOW_MS', 0)),
];
