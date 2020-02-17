<?php

namespace frontend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Payment;

/**
 * PaymentSearch represents the model behind the search form of `common\models\Payment`.
 */
class PaymentSearch extends Payment
{
    /**
     * @var string
     */
    public $date_range;

    /**
     * @var string
     */
    public $client_search;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['profile_id', 'created_at'], 'integer'],
            [['uuid'], 'safe'],
            [['amount'], 'number'],

            [['date_range', 'client_search'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param null|int $profile_id
     * @return ActiveDataProvider
     */
    public function search($params, $profile_id)
    {
        $query = Payment::find();

        $query->andFilterWhere([
            'profile_id' => $profile_id,
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'profile_id' => $this->profile_id,
            'amount' => $this->amount,
            'created_at' => $this->created_at,
        ]);

        if ($this->client_search) {
            $query
                ->joinWith(['profile' => function ($query) {
                    $query->andWhere(['or',
                        ['like', 'profile.uuid', $this->client_search],
                        ['like', 'profile.phone', $this->client_search],
                    ]);
                }]);
        }

        if ($this->date_range) {
            $dates = explode(' - ', $this->date_range);
            @list($dateFrom, $dateTill) = array_map('trim', $dates);
            if ($dateFrom && $dateTill) {
                $dateFrom = static::normalizeDate($dateFrom);
                $dateTill = static::normalizeDate($dateTill);
                $query
                    ->andWhere(['between', 'payment.created_at', $dateFrom, $dateTill]);
            }
        }

        return $dataProvider;
    }

    /**
     * Normalizes date string from RU to EU and returns unix time.
     *
     * @param string $dateTime
     * @return false|int
     */
    private static function normalizeDate(string $dateTime)
    {
        @list($date, $time) = explode(' ', $dateTime);
        @list($d, $m, $y) = explode('/', $date);
        return strtotime("{$y}-{$m}-{$d} {$time}:00 ");
    }
}
