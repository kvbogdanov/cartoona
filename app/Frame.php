<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

/**
 * @property int $id_frame
 * @property int $id_cardtemplate
 * @property int $id_media
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $visible
 * @property string $text
 * @property string $header
 * @property string $font
 * @property int $fontsize
 */
class Frame extends Model implements HasMedia
{
    use HasMediaTrait;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'db_frame';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_frame';

    /**
     * @var array
     */
    protected $fillable = ['id_cardtemplate', 'id_media', 'created_at', 'updated_at', 'visible', 'text', 'header', 'font', 'fontsize'];

    static public $rules = [
        'header' => 'max:150',
        'text' => 'max:5000'
    ];

    public function cardtemplate()
    {
        return $this->hasOne('App\Cardtemplate', 'id_cardtemplate', 'id_cardtemplate');
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('microthumb')
              ->width(50)
              ->height(30)
              //->sharpen(10)
              ->nonOptimized();

        $this->addMediaConversion('thumb')
              ->width(320)
              ->height(240)
              //->sharpen(10)
              ->nonOptimized();

        $this->addMediaConversion('large')
              ->width(900)
              ->height(800)
              ->keepOriginalImageFormat()
              //->sharpen(10)
              ->nonOptimized();
    }

    public function orderInList()
    {

        $sql = 'SELECT * FROM db_frame WHERE id_cardtemplate = :id ORDER BY `order`, id_frame';
        $results = DB::select($sql, ['id' => $this->id_cardtemplate]);

        foreach ($results as $key => $res)
        {
            if($res->id_frame == $this->id_frame)
            {
                $ord = $key;
                break;
            }
        }

        return $ord+1;
    }

    public function usertext($id_card)
    {
        $sql = 'SELECT * FROM db_user_text WHERE id_card = :id AND id_frame = :idf';
        $result = DB::select($sql, ['id' => $id_card, 'idf' => $this->id_frame]);

        return strip_tags($result[0]->content_text??$this->text);
    }

    public function userheader($id_card)
    {
        $sql = 'SELECT * FROM db_user_text WHERE id_card = :id AND id_frame = :idf';
        $result = DB::select($sql, ['id' => $id_card, 'idf' => $this->id_frame]);

        return strip_tags($result[0]->header_text??$this->header);
    }

}
