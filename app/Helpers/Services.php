<?php
namespace App\Helpers;
 
use Illuminate\Support\Facades\DB;

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use Carbon\Carbon;
 
class Services {
    public static function saveEmail($params) {
        DB::beginTransaction();

        $idz = 'MAIL' . Carbon::now()->timestamp. uniqid(). uniqid();

        //handle to_name
        $to_name = '';
        if (isset($params['to_name'])){
            $to_name = $params['to_name'];
        };

        //handle cc
        $cc = '';
        if (isset($params['cc'])){
            $cc = $params['cc'];
        };

        //handle cc
        $bcc = '';
        if (isset($params['bcc'])){
            $bcc = $params['bcc'];
        };

        //UPDATE DATA
        $id = DB::table('send_email')->insert([
            'code' => $idz,
            'type' => $params['type'],
            'subject' => $params['subject'],
            'from' => 'noreply@myseajobs.com',
            'to' => $params['to'],
            'to_name' => $to_name,
            'cc' => $cc,
            // 'bcc' => $bcc,
            'bcc' => 'nurmarief@gmail.com',
            'body' => $params['body'],
            'sent' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::commit();

        return $id;
    }

    public static function encrypt($params){
        switch ($params['type']) {
            case 'FORGOT_PASSWORD':
                $dateNow = Carbon::now();
                $dateNow->addHours(1);
            
                $params['expired'] = $dateNow;
                $valz = implode("###@@###",$params);

                $newCode = Crypt::encryptString($valz);

                break;
            default:
                $newCode = 0;
                # code...
                break;
        }

        return $newCode;
    }

    public static function decrypt($code){
        return Crypt::decryptString($code);
    }
    
}