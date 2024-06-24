<?php
namespace Aman5537jains\AbnDynamicContentPlugin\Models;

use Aman5537jains\AbnCms\Lib\Sluggable;
use Illuminate\Database\Eloquent\Model;

class DynamicContentType extends Model{
    use Sluggable;



    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate'=>false
            ]
        ];
    }
}
