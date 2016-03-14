<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Post;

/**
 * PostSearch represents the model behind the search form about `app\models\Post`.
 */
class PostSearch extends Post
{

    public $status_name;
    public $author_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'create_time', 'update_time', 'author_id'], 'integer'],
            [['title', 'content', 'tags'], 'safe'],
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
        $query = Post::find();
        $query->joinWith(['lookup']);
        $query->joinWith(['author']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 2,
            ],
        ]);

        $this->load($params);

        $dataProvider->setSort([
            'attributes' =>  [
                'status' =>[
                    'asc'=>['tbl_lookup.status_name' => SORT_ASC],
                    'desc'=>['tbl_lookup.status_name' => SORT_DESC],
                    'label'=>'Status Name',
                ],
                'author_id' => [
                    'asc'=>['tbl_user.author_name' => SORT_ASC],
                    'desc'=>['tbl_user.author_name' => SORT_DESC],
                ],
            ],
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
            'author_id' => $this->author_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'tags', $this->tags]);

        $query->andFilterWhere(['like', 'tbl_lookup.status_name', $this->status_name]);
        $query->andFilterWhere(['like', 'tbl_user.autor_name', $this->author_name]);
        return $dataProvider;
    }
}
