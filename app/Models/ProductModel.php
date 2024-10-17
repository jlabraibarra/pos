<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    protected $table = 't_products';
    protected $primaryKey = 'id';

    public function favorite()
    {
        return $this->belongsTo(FavoriteProductModel::class, 'id_product');
    }
}
