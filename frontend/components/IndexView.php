<?php

namespace frontend\components;

use common\models\Profile;
use yii\data\ActiveDataProvider;

class IndexView extends BaseViewModel implements ViewModelInterface
{
    public function __construct()
    {
        $this->data['profilesDP'] = new ActiveDataProvider([
            'query' => Profile::find(),
            'sort' => [
                'defaultOrder' => [
                    'l_name' => SORT_ASC,
                    'f_name' => SORT_ASC,
                ],
            ],
        ]);
    }
}