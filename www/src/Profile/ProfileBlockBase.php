<?php 

namespace App\Profile;

// Базовый класс для блоков профиля
abstract class ProfileBlockBase
{
    protected $loadedData;

    protected $errors = [];


    abstract static public function code();


    abstract static public function title();
    

    abstract static public function description();
    
    abstract static public function read($profile);

    abstract public function render();

    public function load($data)
    {
        // Метод для загрузки данных в блок профиля
        $this->loadedData = $data;
        return $this;
    }

    /**
     * Метод для получения свойств блока профиля
     */
    abstract public function properties(): array;
    
    /**
     * Метод для обработки данных блока профиля
     */
    abstract public function handle();

    /**
     * Метод для получения ошибок после обработки блока профиля
     */
    protected function errors()
    {
        return $this->errors; 
    }

}
