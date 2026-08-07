<?php

// Create a temporary, writable folder for compiled Blade views on Vercel
if (! is_dir('/tmp/views')) {
    mkdir('/tmp/views', 0755, true);
}

// Forward the request to the normal Laravel entry point
require __DIR__ . '/../public/index.php';
