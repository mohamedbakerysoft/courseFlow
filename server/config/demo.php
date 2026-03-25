<?php

return [
    'enabled' => env('APP_DEMO', false),
    'admin_email' => env('DEMO_ADMIN_EMAIL', 'admin@example.com'),
    'admin_password' => env('DEMO_ADMIN_PASSWORD', 'password'),
    'student_email' => env('DEMO_STUDENT_EMAIL', 'student@demo.com'),
    'student_password' => env('DEMO_STUDENT_PASSWORD', 'password'),
];
