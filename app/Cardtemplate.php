<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Image\Manipulations;

/**
 * @property int $id
 * @property int $id_user
 * @property integer $date_create
 * @property integer $status
 * @property int $delay
 * @property int $effect
 * @property integer $adult
 * @property integer $sex
 * @property string $url
 * @property integer $lang
 */
class Cardtemplate extends Model implements HasMedia
{
    use HasMediaTrait;

    public const STATE_UNASSIGNED = 0;
    public const STATE_ACTIVE = 1;
    public const STATE_HIDDEN = 2;
    public const STATE_DELETED = 4;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'db_cardtemplate';

    protected $primaryKey = 'id_cardtemplate';

    static public $rules = [
        'url' => 'nullable|unique:db_cardtemplate',
    ];

    /**
     * @var array
     */
    protected $fillable = ['id_user', 'date_create', 'status', 'delay', 'effect', 'adult', 'sex', 'url', 'lang'];


    public function frames()
    {
        return $this->hasMany('App\Frame', 'id_cardtemplate')->orderBy('order');
    }

    public function types()
    {
        return $this->belongsToMany('App\CardtemplateType', 'db_cardtemplate_type', 'id_cardtemplate', 'id_cardtemplate')->orderBy('order');
    }

    public function getRouteKeyName()
    {
        return 'id_cardtemplate';
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('microthumb')
            ->width(50)
            ->height(30)
            ->sharpen(10)
            ->nonOptimized();

        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CROP, 320, 240)
            ->sharpen(10)
            ->nonOptimized();

        $this->addMediaConversion('share')
            ->width(968)
            ->height(504)
            //->keepOriginalImageFormat()
            //->sharpen(10)
            ->nonOptimized();
    }

}
