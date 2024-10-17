<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserModel extends Authenticatable
{
    protected $table = 't_users';

    use Notifiable;

    // Definir las columnas o atributos del modelo
    protected $fillable = [
        'name', 'email', 'password',
    ];
}
