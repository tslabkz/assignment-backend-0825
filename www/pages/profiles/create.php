<?php 

require_once (dirname(__DIR__, 2) . '/index.php'); 

use App\Models\User; 

echo '<h1>New Profile</h1> <p>Inserting new profile into profile table</p>'; 

// $userModel = new User(); 
// $userId = $userModel->insert(['name' => 'John Doe', 'email' => 'john@example.com']); 
// $user = $userModel->find($userId); 

// print_r($user); 

$profileForm = \App\Profile\Units\ProfileForm::new();

$blocks = $profileForm->getBlocks();

foreach ($blocks as $block) {
    echo '<h2>' . get_class($block) . '</h2>';
    // Assuming each block has a render method
    if (method_exists($block, 'render')) {
        echo $block->render();
    } else {
        echo 'No render method available for this block.';
    }
}
