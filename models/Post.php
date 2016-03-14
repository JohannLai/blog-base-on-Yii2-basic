<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use app\models\Tag;
use app\models\Comment;
use yii\web\UploadedFile;


/**
 * This is the model class for table "tbl_post".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $tags
 * @property integer $status
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $author_id
 */
class Post extends \yii\db\ActiveRecord
{

    const STATUS_DRAFT=1;
    const STATUS_PUBLISHED=2;
    const STATUS_ARCHIVED=3;

    private $_oldTags;
    public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'status'], 'required'],
            [['status', 'create_time', 'update_time'],'integer'],
            [['content', 'tags'], 'string'],
            [['title'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '题目',
            'content' => '内容',
            'tags' => '标签',
            'status' => '状态',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
            'author_id' => '作者',
            'images' => '作者',
        ];
    }

    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['post_id' => 'id'])
            ->where('status = '.Comment::STATUS_APPROVED)
            ->orderBy('id');
    }

    public function getCommentCount()
    {
        return Comment::find()->where(['post_id' => $this->id,'status' => Comment::STATUS_APPROVED])->count();
    }

    public function getAuthor()
    {
         return $this->hasOne(User2::className(), ['id' => 'author_id']);
    }

    public function getUrl()
    {
        return Yii::$app->urlManager->createUrl(
            ['post/detail','id'=>$this->id,'title'=>$this->title]
        );
    }
    public function getLookup(){
        return $this->hasOne(Lookup::className(),['id' => 'status']);
    }


    public function getTagLinks()
    {
        $links=array();
        foreach(Tag::string2array($this->tags) as $tag)
            $links[]=Html::a(Html::encode($tag),array('post/index', 'tag'=>$tag));
        return $links;
    }

    public function addComment($comment)
    {
        if(Yii::$app->params['commentNeedApproval'])
            $comment->status=Comment::STATUS_PENDING;
        else
            $comment->status=Comment::STATUS_APPROVED;

        $comment->post_id=$this->id;

        return $comment->save();
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->_oldTags=$this->tags;
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {

            $this->author_id = 1;
            if($insert)
            {
                $this->create_time=time();
                $this->update_time=time();
            }
            else
            {
                 $this->update_time=time();
            }
            return true;
        }
        else
            return false;
    }

    public function afterSava($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        Tag::UpdateFrequency($this->_oldTags, $this->tags);
    }
}
