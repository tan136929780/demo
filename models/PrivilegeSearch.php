<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PrivilegeSearch represents the model behind the search form about `app\models\Privilege`.
 */
class PrivilegeSearch extends Privilege
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'id',
                    'pid',
                    'sequence',
                    'depth',
                    'created_at',
                    'updated_at'
                ],
                'integer'
            ],
            [
                [
                    'name',
                    'params',
                    'controller',
                    'action',
                    'deleted'
                ],
                'safe'
            ],
        ];
    }

    /**
     * @inheritdoc
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
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Privilege::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $id = $this->id;

        if (intval($this->pid) > 0) {
            $pidArray = Privilege::find()
                                 ->select('id')
                                 ->where(['pid' => $this->pid])
                                 ->asArray()
                                 ->all();

            $id = array_column($pidArray, 'id');
        }

        $query->andFilterWhere(['id' => $id])
              ->andFilterWhere([
                  'like',
                  'name',
                  $this->name
              ])
              ->andFilterWhere([
                  'like',
                  'params',
                  $this->params
              ])
              ->andFilterWhere([
                  'like',
                  'controller',
                  $this->controller
              ])
              ->andFilterWhere([
                  'like',
                  'action',
                  $this->action
              ])
              ->andFilterWhere([
                  'like',
                  'deleted',
                  $this->deleted
              ]);

        return $dataProvider;
    }
}
