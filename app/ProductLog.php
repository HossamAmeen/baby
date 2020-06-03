<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductLog extends Model
{
    //

    const add_place_order = 'add_place_order';
    const return_place_order = 'return_place_order';
    const add_store_order = 'add_store_order';
    const edit_store_order = 'edit_store_order';
    const add_place_store_order = 'add_place_store_order';
    const edit_place_store_order = 'edit_place_store_order';
    const add_order = 'add_order';
    const edit_order = 'edit_order';

    const add_main_quantity = 'add_main_quantity';
    const add_store_quantity = 'add_store_quantity';
    const add_sub_quantity = 'add_sub_quantity';
    const sarf_main_quantity = 'sarf_main_quantity';
    const sarf_store_quantity = 'sarf_store_quantity';
    const sarf_sub_quantity = 'sarf_sub_quantity';
    const change_main_main = 'change_main_main';
    const change_main_sub = 'change_main_sub';
    const change_main_store = 'change_main_store';
    const change_sub_main = 'change_sub_main';
    const change_sub_sub = 'change_sub_sub';
    const change_sub_store = 'change_sub_store';
    const change_store_main = 'change_store_main';
    const change_store_sub = 'change_store_sub';
    const change_store_store = 'change_store_store';
    const minus_main = 'minus_main';
    const minus_sub = 'minus_sub';
    const minus_store = 'minus_store';
    const return_main = 'return_main';
    const return_sub = 'return_sub';
    const return_store = 'return_store';

    const return_order = 'return_order';
    const cancel_order = 'cancel_order';
    const return_store_order = 'return_store_order';
    const cancel_store_order = 'cancel_store_order';
    const return_place_store_order = 'return_place_store_order';
    const cancel_place_store_order = 'cancel_place_store_order';

    protected $table = 'product_logs';
    protected $fillable = ['product_id', 'quantity', 'stock', 'main_stock', 'store_stock'
        , 'sub_stock', 'action' , 'user_id'];

}
