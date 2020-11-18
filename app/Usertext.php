<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id_user_text
 * @property int $id_card
 * @property int $id_frame
 * @property int $id_user
 * @property string $created_at
 * @property string $updated_at
 * @property string $header_text
 * @property string $content_text
 * @property int $state
 */
class Usertext extends Model
{

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'db_user_text';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'id_user_text';

    /**
     * @var array
     */
    protected $fillable = ['id_card', 'id_frame', 'id_user', 'created_at', 'updated_at', 'header_text', 'content_text', 'state'];

    static public $rules = [
        'header_text' => 'max:150',
        'content_text' => 'max:5000'
    ];
}
