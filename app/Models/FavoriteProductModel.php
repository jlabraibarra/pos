<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteProductModel extends Model
{
    protected $table = 't_favorite_product';
    protected $primaryKey = 'id';

    public function product()
    {
        return $this->belongsTo(ProductModel::class, 'id_product');
    }
}
