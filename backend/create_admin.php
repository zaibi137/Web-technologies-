<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

// Create admin user
$admin = \App\Models\User::create([
    'name' => 'Admin User',
    'email' => 'admin@opalhaven.com',
    'password' => bcrypt('password123'),
    'role' => 'admin',
    'phone' => '+1-555-0100',
    'is_active' => true
]);

echo "✓ Admin user created successfully!\n";
echo "Email: admin@opalhaven.com\n";
echo "Password: password123\n";
