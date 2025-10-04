<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CarListingSearch represents the model behind the search form of `common\models\CarListing`.
 */
class CarListingSearch extends CarListing
{
    public $minPrice;
    public $maxPrice;
    public $minYear;
    public $maxYear;
    
    public function rules()
    {
        return [
            [['year'], 'integer'],
            [['title', 'make', 'model', 'status'], 'safe'],
            [['minPrice', 'maxPrice'], 'number'],
            [['minYear', 'maxYear'], 'integer'],
        ];
    }
    
    public function scenarios()
    {
        
        return Model::scenarios();
    }

    
    public function search($params)
    {
        $query = CarListing::find();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);
        
        $this->load($params);
        
        if (!$this->validate()) {
            return $dataProvider;
        }

        
        $query->andFilterWhere([
            'year' => $this->year,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'make', $this->make])
            ->andFilterWhere(['like', 'model', $this->model])
            ->andFilterWhere(['>=', 'price', $this->minPrice])
            ->andFilterWhere(['<=', 'price', $this->maxPrice])
            ->andFilterWhere(['>=', 'year', $this->minYear])
            ->andFilterWhere(['<=', 'year', $this->maxYear]);

        return $dataProvider;
    }
}
