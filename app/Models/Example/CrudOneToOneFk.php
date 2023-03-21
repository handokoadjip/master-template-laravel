<?php

namespace App\Models\Example;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrudOneToOneFk extends Model
{
    use HasFactory;
    use \App\Traits\TraitUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'crud_one_to_one_fk';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'crud_one_to_one_fk_id';

    const CREATED_AT = 'crud_one_to_one_fk_dibuat_pada';
    const UPDATED_AT = 'crud_one_to_one_fk_diubah_pada';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'crud_one_to_one_fk_id',
        'crud_one_to_one_fk_crud_one_to_one_pk_id',
        'crud_one_to_one_fk_telp',
    ];

    /**
     * Get the user that owns the phone.
     */
    public function identitas()
    {
        return $this->belongsTo(CrudOneToOnePk::class, 'crud_one_to_one_fk_crud_one_to_one_pk_id', 'crud_one_to_one_pk_id');
    }
}
