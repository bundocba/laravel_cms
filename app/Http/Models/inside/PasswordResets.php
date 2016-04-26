<?php

use Illuminate\Database\Eloquent\Model;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Models\inside;

/**
 * Description of PasswordResets
 *
 * @author MRBun
 */
class PasswordResets extends \Illuminate\Database\Eloquent\Model{
    protected $table = 'password_resets';
    
    protected $fillable = [
        'email','token','password','password_confirmation'
    ];
}
