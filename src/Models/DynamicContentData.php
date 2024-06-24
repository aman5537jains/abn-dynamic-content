<?php
namespace Aman5537jains\AbnDynamicContentPlugin\Models;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
class DynamicContentData extends Model{
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

    public function dynamicContentDataAttribute(){
        return $this->hasMany(DynamicContentDataAttribute::class);
    }
}
