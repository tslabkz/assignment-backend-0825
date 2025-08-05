<?php 

namespace App\Profile\Blocks;

use App\Profile\ProfileBlockBase;
use App\Html\HtmlCheckboxList;
use App\Models\OlimpicPredmet;

// Блок который собирает информацию о достижениях олимпийцев

class OlimpicBlock extends ProfileBlockBase
{
    // Здесь можно добавить методы и свойства, специфичные для блока олимпийских достижений 
    protected $data;
    protected $user;
    protected $profile;
    
    static public function code() 
    {
        return 'Olimpic';
    }

    static public function title() 
    {
        return 'Олимпиадные занятия';
    }

    static public function description() 
    {
        return 'Блок для отображения и редактирования Олимпиадных занятий пользователя';
    }
    
    public function read($profile)
    {
        // Логика для чтения данных Олимпиадных занятий из профиля
        $this->profile = $profile;
        $olimpic_users = (new \App\Models\OlimpicUser())->findWhere(
            ['user_id' => $profile['user_id']]); // Assuming profile has a user_id property 
        $this->data = [
            'olimpic_predmet_ids' => array_column($olimpic_users ?? [], 'olimpic_predmet_id') ?? [],
        ];
        return true;
    }


    public function view()
    {
        $html = ''; 
        $comma = "";
        $allPredmets = (new OlimpicPredmet())->all();
        foreach ($allPredmets as $predmet) {
            if (in_array($predmet['id'], $this->data['olimpic_predmet_ids'])) {
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
         
        $olimpic_predmet_ids =[];
        if (isset($this->loadedData['olimpic_predmet_ids'])) {
            $olimpic_predmet_ids = $this->loadedData['olimpic_predmet_ids'];
        } 

        if (empty($this->errors)) {
            $insertedIds = array_diff($olimpic_predmet_ids, $this->data['olimpic_predmet_ids']);
            $deleteIds = array_diff($this->data['olimpic_predmet_ids'], $olimpic_predmet_ids);
            // Если ошибок нет, можно сохранить данные в профиль
            foreach ($insertedIds as $id) {
                (new \App\Models\OlimpicUser())->insert([
                    'user_id' => $this->profile['user_id'],
                    'olimpic_predmet_id' => $id,
                ]);
            }
            foreach ($deleteIds as $id) {
                (new \App\Models\OlimpicUser())->deleteWhere([
                    'user_id' => $this->profile['user_id'],
                    'olimpic_predmet_id' => $id,
                ]);
            }
        }
        // Обновляем профиль, чтобы отметить, что блок олимпиад был изменен
        $olimpic_predmet_ids = (new \App\Models\OlimpicUser())->findWhere(
            ['user_id' => $this->profile['user_id']]);
        $olimpicIsset = empty($olimpic_predmet_ids) ? 0 : 1;
        (new \App\Models\Profile())->update(
            $this->profile['id'], 
            [
                'olimpic_block' => $olimpicIsset,
            ]
        );
    }

    public function properties(): array
    {
        return [
            [
                'name' => 'olimpic_predmet_ids',
                'label' => 'Олимпиадные занятия',
                'html' => HtmlCheckboxList::class,
                'form_asset' => function() {
                    return [
                        'items' => (new OlimpicPredmet)->all(), // Assuming this returns an associative array of items
                        'placeholder' => 'Выберите Олимпиадные занятия',
                    ];
                }
            ],
        ];
    }
}