<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Document extends Model
{
    //
    protected $appends = ['id'];

    public function user_details()
    {
        return $this->hasOne('App\User', '_id', 'user_id');
    }

    public function getIconAttribute()
    {

        $extensions = [
            'doc' => 'icon-file-word',
            'docx' => 'icon-file-word',
            'pdf' => 'icon-file-pdf',
            'xls' => 'icon-file-excel',
            'xlsx' => 'icon-file-excel',
        ];

        return array_get($extensions, $this->attributes['type'], 'icon-file-empty');
    }
    public function getIconColorAttribute()
    {
        $extensions = [
            'doc' => 'primary',
            'docx' => 'primary',
            'pdf' => 'danger',
            'xls' => 'success',
            'xlsx' => 'success',
        ];

        return array_get($extensions, $this->attributes['type'], 'info');
    }
}
