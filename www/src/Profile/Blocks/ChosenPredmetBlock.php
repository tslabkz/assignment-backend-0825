<?php 

namespace App\Profile\Blocks;

use App\Profile\ProfileBlockBase;
use App\Html\HtmlSelect;
use App\Models\ChosenPredmet;


// Блок, который собирает информацию о выбранном предмете
// Этот блок может быть использован для отображения или редактирования информации о предмете, который был выбран пользователем
// четвертный предмет
class ChosenPredmetBlock extends ProfileBlockBase
{
    protected $data;
    protected $user;
    protected $profile;
    
    static public function code() 
    {
        return 'chosen';
    }

    static public function title() 
    {
        return 'Выбранное занятие';
    }

    static public function description() 
    {
        return 'Блок для отображения и редактирования выбранного предмета пользователя';
    }

    public function read($profile)
    {
        // Логика для чтения данных Олимпиадных занятий из профиля
        $this->profile = $profile;
        $users = (new \App\Models\ChosenPredmetUser())->findWhere([
            'user_id' => $profile['user_id']
        ]) ?: [];
        $first = $users[0] ?? [];
        $this->data = [
            'chosen_predmet_id' => $first['chosen_predmet_id'] ?? null,
        ];
        return true;
    }


    public function view()
    {
        $html = '';
        $all = (new ChosenPredmet())->all();
        // Отображаем только единственный выбранный предмет
        foreach ($all as $item) {
            if ($item['id'] === $this->data['chosen_predmet_id']) {
                $html = '<span class="badge bg-primary">' . $item['name'] . '</span>';
                break;
            }
        }
        return '<div class="' . static::code() . '-block">' . $html . '</div>';
    }

    /**
     * Метод для обработки данных блока профиля
     */
    public function handle() {
        $chosen_id = $this->loadedData['chosen_predmet_id'] ?? null;

        if (empty($this->errors)) {
            (new \App\Models\ChosenPredmetUser())->deleteWhere([
                'user_id' => $this->profile['user_id'],
            ]);
            if ($chosen_id !== null) {
                (new \App\Models\ChosenPredmetUser())->insert([
                    'user_id' => $this->profile['user_id'],
                    'chosen_predmet_id' => $chosen_id,
                ]);
            }
        }
        // обновляем флаг в профиле
        $entries = (new \App\Models\ChosenPredmetUser())->findWhere([
            'user_id' => $this->profile['user_id'],
        ]) ?: [];
        $isset = empty($entries) ? 0 : 1;
        (new \App\Models\Profile())->update(
            $this->profile['id'],
            ['chosen_predmet_block' => $isset]
        );
    }

    public function properties(): array
    {
        return [
            [
                'name' => 'chosen_predmet_id',
                'label' => 'Выбранный предмет занятия',
                'html' => HtmlSelect::class,
                'form_asset' => function() {
                    return [
                        'items' => 
                        [1 => 'Физика', 2 => 'Химия', 3 => 'Биология', 4 => 'История', 5 => 'География', 6 => 'Литература', 7 => 'Математика', 8 => 'Информатика'],

                         // Assuming this returns an associative array of items
                        'placeholder' => 'Выберите единственное занятие',
                    ];
                }
            ],
        ];
    }



}