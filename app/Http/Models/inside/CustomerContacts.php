<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Models\inside;
use Illuminate\Database\Eloquent\Model;
/**
 * Description of CustomerContacts
 *
 * @author MRBun
 */
class CustomerContacts extends Model{
    protected $table='customer_contacts';
    protected $fillable=[
        'company_name',
        'full_name',
        'created_at',
        'updated_at',
        'is_deleted',
    ];
    public static function getAll($isDeleted = 0) {
        $listCustomerContacts = self::select('*')
                ->where('is_deleted', '=', $isDeleted);
        $list = $listCustomerContacts->get()->toArray();
        return $list;
    }
}
