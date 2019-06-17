<?php
/**
 * Created by PhpStorm.
 * User: z0dd
 * Date: 30.03.2019
 * Time: 21:06
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function sendgrid(Request $request)
    {
        $filename = '/home/zodd/www/12space/storage/logs/sendgrid.log';
        if ($handle = fopen($filename,'a+')) {
            fwrite($handle, json_encode($request->all())."\r\n\n");
        }
        return "success";
    }
}
