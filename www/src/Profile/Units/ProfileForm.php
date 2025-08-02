<?php 

namespace App\Profile\Units;

use App\Profile\ProfileStruct;

class ProfileForm
{
    // This class can be used to handle profile form logic
    // For example, it can include methods for validation, submission, etc.
    protected $profileStruct;

    function __construct($user = null)
    {
        $this->profileStruct = new ProfileStruct($user);
    }

    /**
     * Constructor to initialize the profile form with a profile object
     * 
     * @param mixed $profile
     */
    public static function new()
    {
        return new self();
    }

    /**
     * @var int $id user id
     * @return ProfileForm
     */
    public static function of($id) 
    {
        $user = ProfileStruct::findUser($id);
        if (is_null($user)) {
            throw new \Exception("User not found");
        }
        return new self($user);
    } 

    public function getBlocks()
    {
        return $this->profileStruct->getBlocks();
    }

} 
