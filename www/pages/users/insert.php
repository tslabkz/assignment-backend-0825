<?php 

require_once (dirname(__DIR__, 2) . '/index.php'); 

use App\Models\User; 

echo '<h1>New User</h1> <p>Inserting new user into user table</p>'; 

$userModel = new User(); 
$userId = $userModel->insert(['name' => 'John Doe', 'email' => 'john@example.com']); 
$user = $userModel->find($userId); 

print_r($user); 
