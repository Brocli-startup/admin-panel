<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TranslationTrait;

class ServiceFaq extends Model
{
    use HasFactory;
    use TranslationTrait;
    
    protected $table = 'service_faqs';
    protected $fillable = [
        'title', 'description', 'service_id', 'status'
    ];
    protected $casts = [
        'service_id'    => 'integer',
        'status'    => 'integer',
    ];
    
    public function translations()
    {
        return $this->morphMany(Translations::class, 'translatable');
    }

    public function translate($attribute, $locale = null)
    {
        $locale = $locale ?? app()->getLocale() ?? 'en';
        if($locale !== 'en'){
            $translation = $this->translations()
            ->where('attribute', $attribute)
            ->where('locale', $locale)
            ->value('value');

            return $translation !== null ? $translation : '';
        }
        return $this->$attribute;
    }
    
    public function service(){
        return $this->belongsTo(Service::class,'service_id', 'id');
    }
}