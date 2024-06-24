<?php
namespace Aman5537jains\AbnDynamicContentPlugin\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class DynamicContentTypeView extends Model{

    use Sluggable;



    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'onUpdate'=>false
            ]
        ];
    }

    public function dynamicContentType(){
        return $this->belongsTo(DynamicContentType::class);
    }
}
