<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    public function formatarData() {
        $data = date_create($this->dataNasc);
        return date_format($data, "d-m-Y");
    }
}
