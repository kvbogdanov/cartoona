<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id_card
 * @property int $id_cardtemplate
 * @property int $id_user
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $content
 * @property int $views
 * @property int $shares
 * @property int $likes
 */
class Card extends Model
{

    public const STATE_UNPAYED = 0;
    public const STATE_PAYED = 1;
    public const STATE_DELETED = 4;

    private $alive = 30;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'db_card';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'id_card';

    /**
     * @var array
     */
    protected $fillable = ['id_cardtemplate', 'id_user', 'created_at', 'updated_at', 'content', 'views', 'shares', 'likes'];

    public function cardtemplate()
    {
        return $this->hasOne('App\Cardtemplate', 'id_cardtemplate', 'id_cardtemplate');
    }    

    public function usertext()
    {
        return $this->hasMany('App\Usertext', 'id_card');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id_user');
    }

    public function daysLeft()
    {
        $start = strtotime($this->created_at);
        $delta = floor((time() - $start)/(24*3600));

        return $this->alive - $delta;
    }
}
