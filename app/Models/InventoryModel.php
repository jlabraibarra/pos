<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryModel extends Model
{
    protected $table = 't_inventories';
    protected $primaryKey = 'id';

    public function product()
    {
        return $this->belongsTo(ProductModel::class, 'id_product');
    }

}
