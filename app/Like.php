<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id_like
 * @property int $id_cardtemplate
 * @property int $id_card
 * @property string $created_at
 * @property string $updated_at
 * @property integer $ip
 */
class Like extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'db_like';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'id_like';

    /**
     * @var array
     */
    protected $fillable = ['id_cardtemplate', 'id_card', 'created_at', 'updated_at', 'ip'];

}
