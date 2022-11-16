<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{

    const SQL_READ_NODE = 'SQL_READ';


    use HasFactory;


    public static function LogToDb($type =null, $message = null, $userId = null, $code = null)
    {
        $logs         = new Logs();
        $logs->type   =  $type ?? 'warning';
        $logs->user_id=  $userId ?? 1;
        $logs->code   =  $code ?? self::SQL_READ_NODE;
        $logs->message=  $message ?? '';
        $logs->save();
    }
}
