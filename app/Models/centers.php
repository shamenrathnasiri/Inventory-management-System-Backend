<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;


class centers extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'center_id');
    }

    public function inventoryStocks()
    {
        return $this->hasMany(inventory_stock::class, 'center_id');
    }
}
