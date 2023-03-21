<?php

namespace App\Models\Example;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crud extends Model
{
    use HasFactory;
    use \App\Traits\TraitUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'crud';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'crud_id';

    const CREATED_AT = 'crud_dibuat_pada';
    const UPDATED_AT = 'crud_diubah_pada';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'crud_id',
        'crud_nama',
        'crud_foto',
    ];
}
