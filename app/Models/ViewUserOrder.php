<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewUserOrder extends Model
{
    protected $table = 'view_user_orders';

    protected $primaryKey = 'order_id';

    public $timestamps = false;
}
