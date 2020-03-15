<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/27
 * Time: 10:59
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'district_id', 'name', 'rank', 'image', 'description', 'num'
    ];

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_certificate', 'certificate_id', 'user_id')
            ->as('user_certificate');
    }
}