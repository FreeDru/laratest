<?php

namespace App\Http\Controllers\helpers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\order;

class helpController extends Controller
{
    public static function checkValidMail($mail) {
        if(!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            return 0;
        }else {
            return 1;
        }
    }

    public static function checkValidPhone(string $telephone) {
        if (preg_match("/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/", $telephone)) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function checkUseMail($mail) {

        $mail_find = Client::where('mail', $mail)->first();

        if(isset($mail_find->id)) {
            return 0;
        }else {
            return 1;
        }

    }

    public static function checkUsePhone($phone) {

        $mail_find = Client::where('phone', $phone)->first();

        if(isset($mail_find->id)) {
            return 0;
        }else {
            return 1;
        }

    }

    public static function checkMoscowTime($time) {
        if($time < -10 OR $time > 10) {
            return 0;
        }else {
            return 1;
        }
    }
}
