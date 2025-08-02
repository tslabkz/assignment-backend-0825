<?php 

namespace App\Profile; 

use App\Profile\Blocks\FioBlock; 

/**
 * стркуктура профиля пользователя (школьника)
 */

class ProfileStruct 
{ 
    protected $user;
    protected $profile;
    protected $blocks = [];

    protected $blocks_classes = [ 
        FioBlock::class, 
        // \App\Profile\Blocks\BirthdateBlock::class, 
        // \Profile\Blocks\FacultativeBlock::class, 
        // \Profile\Blocks\SportBlock::class, // задание 
        // \Profile\Blocks\ChosenPredmetBlock::class, 
        // \Profile\Blocks\OlimpicBlock::class, // задание 
    ]; 

    public function __construct($user = null) 
    { 
        $this->user = $user; 
        if (!is_null($user)) {
            $profileModel = new \App\Models\Profile(); // Assuming Profile model exists
            $userId = $user->id ?? null; // Assuming user has an id property
            $this->profile = $profileModel->findWithConditions(['user_id' => $userId]); // Fetching profile
            if (is_null($this->profile)) {
                $newProfileId = $profileModel->insert(['user_id' => $userId]); // Create a new profile if not found
                $this->profile = $profileModel->find($newProfileId); // Fetch the newly created profile
            }
        }
        $this->initBlocks();
    }

    protected function initBlocks() 
    { 
        foreach ($this->blocks_classes as $blockClass) {
            if (class_exists($blockClass)) {
                $newBlock = new $blockClass($this->profile);
                $this->blocks[$newBlock->code()] = $newBlock;
            } else {
                throw new \Exception("Block class $blockClass does not exist");
            }
        }
    }

    public function getBlocks() 
    { 
        return $this->blocks; 
    }

    public static function findUser($userId) 
    {
        $userModel = new \App\Models\User();
        return $userModel->find($userId);
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getProfile() 
    { 
        return $this->profile; 
    }

}
