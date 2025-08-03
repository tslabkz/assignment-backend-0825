<?php 

namespace App\Profile\Blocks;

use App\Html\HtmlCheckboxList;
use App\Models\FacultativePredmet;
use App\Profile\ProfileBlockBase;

// блок, который собирает информацию о факультативных занятиях

class FacultativeBlock extends ProfileBlockBase
{
    protected $data;
    protected $user;
    protected $profile;
    
    // Здесь можно добавить методы и свойства, специфичные для блока ФИО
    static public function code() 
    {
        return 'facultative';
    }

    static public function title() 
    {
        return 'Факультативные занятия';
    }

    static public function description() 
    {
        return 'Блок для отображения и редактирования факультативных занятий пользователя';
    }
    
    public function read($profile)
    {
        // Логика для чтения данных факультативных занятий из профиля
        $this->profile = $profile;
        $facultative_users = (new \App\Models\FacultativeUser())->findWhere(
            ['user_id' => $profile['user_id']]); // Assuming profile has a user_id property 
        $this->data = [
            'facultative_predmet_ids' => array_column($facultative_users ?? [], 'facultative_predmet_id') ?? [],
        ];
        return true;
    }


    public function view()
    {
        $html = ''; 
        $comma = "";
        $allPredmets = (new FacultativePredmet())->all();
        foreach ($allPredmets as $predmet) {
            if (in_array($predmet['id'], $this->data['facultative_predmet_ids'])) {
                $html .= $comma . '<span class="badge bg-primary">' . $predmet['name'] . '</span> ';
                $comma = ", ";
            } 
        }
            
        return '<div class="' . static::code() . '-block">' . $html . '</div>';
    }

    /**
     * Метод для обработки данных блока профиля
     */
    public function handle() {
        // Логика для обработки данных ФИО
         
        $facultative_predmet_ids =[];
        if (isset($this->loadedData['facultative_predmet_ids'])) {
            $facultative_predmet_ids = $this->loadedData['facultative_predmet_ids'];
        } else {
            $this->errors[] = 'Данные Фамилии не загружены';
        }

        if (empty($this->errors)) {
            $insertedIds = array_diff($facultative_predmet_ids, $this->data['facultative_predmet_ids']);
            $deleteIds = array_diff($this->data['facultative_predmet_ids'], $facultative_predmet_ids);
            // Если ошибок нет, можно сохранить данные в профиль
            foreach ($insertedIds as $id) {
                (new \App\Models\FacultativeUser())->insert([
                    'user_id' => $this->profile['user_id'],
                    'facultative_predmet_id' => $id,
                ]);
            }
            foreach ($deleteIds as $id) {
                (new \App\Models\FacultativeUser())->deleteWhere([
                    'user_id' => $this->profile['user_id'],
                    'facultative_predmet_id' => $id,
                ]);
            }
        }

        // if (isset($this->loadedData['name'])) {
        //     $name = trim($this->loadedData['name']);
        //     if (empty($name)) {
        //         $this->errors[] = 'Имя не может быть пустым';
        //     } 
        // } else {
        //     $this->errors[] = 'Данные Имени не загружены';
        // }

        // if (isset($this->loadedData['lastname'])) {
        //     $lastname = trim($this->loadedData['lastname']);            
        // } 

        // if (empty($this->errors)) {
        //     // Если ошибок нет, можно сохранить данные в профиль
        //     (new User())->update(
        //         $this->user['id'], 
        //         [
        //             'surname' => $surname,
        //             'name' => $name,
        //             'lastname' => $lastname,
        //             // 'email' => $email
        //         ]
        //     ); // Assuming update method exists

        // }
    }

    public function properties(): array
    {
        return [
            [
                'name' => 'facultative_predmet_ids',
                'label' => 'Факультативные занятия',
                'html' => HtmlCheckboxList::class,
                'form_asset' => function() {
                    return [
                        'items' => (new FacultativePredmet)->all(), // Assuming this returns an associative array of items
                        'placeholder' => 'Выберите факультативные занятия',
                    ];
                }
            ],
        ];
    }
    
}
