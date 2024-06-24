<?php
namespace Aman5537jains\AbnDynamicContentPlugin\Models;

use Aman5537jains\AbnCms\Lib\Sluggable;
use Illuminate\Database\Eloquent\Model;

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
