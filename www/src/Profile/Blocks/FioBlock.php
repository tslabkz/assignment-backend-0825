<?php 

namespace App\Profile\Blocks;

use App\Profile\ProfileBlockBase;

// Базовый класс для блоков профиля

class FioBlock extends ProfileBlockBase
{
    // Здесь можно добавить методы и свойства, специфичные для блока ФИО
    static public function code() 
    {
        return 'fio';
    }

    static public function title() 
    {
        return 'ФИО';
    }

    static public function description() 
    {
        return 'Блок для отображения и редактирования ФИО пользователя';
    }
    
    static public function read($profile)
    {
        // Логика для чтения данных ФИО из профиля
        // return $profile->fio ?? '';
    }

    public function render()
    {
        // Логика для отображения блока ФИО
        // Например, можно вернуть HTML-код для отображения ФИО
        return '<div class="fio-block">ФИО: ' . htmlspecialchars($this->loadedData['fio'] ?? '') . '</div>';
    }

    

    public function properties(): array
    {
        // Возвращает свойства блока ФИО
        return [
            'name' => 'fio',
            'label' => 'ФИО',
            'type' => 'text',
            'required' => true,
            'value' => $this->loadedData['fio'] ?? '',
        ];
    }
    
    /**
     * Метод для обработки данных блока профиля
     */
    public function handle() {
        // Логика для обработки данных ФИО
        if (isset($this->loadedData['fio'])) {
            $fio = trim($this->loadedData['fio']);
            if (empty($fio)) {
                $this->errors[] = 'ФИО не может быть пустым';
            } else {
                // Сохранение ФИО в профиль
                // $this->profile->fio = $fio; // Assuming profile has a fio property
                // $this->profile->save(); // Assuming save method exists
            }
        } else {
            $this->errors[] = 'Данные ФИО не загружены';
        }
    }
}