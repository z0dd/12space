<?php
/**
 * Created by PhpStorm.
 * User: z0dd
 * Date: 30.03.2019
 * Time: 21:06
 */

namespace App\Http\Controllers;


use App\SendgridNotification;
use Illuminate\Http\Request;

/**
 * Class WebhookController
 *
 * @package App\Http\Controllers
 */
class WebhookController extends Controller
{
    /**
     * @param Request $request
     *
     * @return string
     */
    public function sendgrid(Request $request)
    {
        $events = $request->all();
        $errors = null;

        foreach ($events as $event) {
            if (empty($event['sg_message_id'])) {
                $errors .= "\nWebhookParse:'sg_message_id' not found";
                continue;
            }

            $messageId = explode('.', $event['sg_message_id']);

            if (sizeof($messageId) !== 3) {
                $errors.= "\nWebhookParse:'sg_message_id' wrong format";
                continue;
            }

            $messageId = $messageId[0];

            $notification = SendgridNotification::where('x_message_id', $messageId)->first();

            if (is_null($notification)){
                $errors.= "\nWebhookParse: not found notification messageId - {$messageId}";
                continue;
            }

            if (!empty($event['event'])) {
                $notification->status = $event['event'];
                $notification->save();
            }
        }

        if (false == is_null($errors)) {
            $filename = '/home/zodd/www/12space/storage/logs/sendgrid.log';
            if ($handle = fopen($filename,'a+')) {
                fwrite($handle, "RequestData: ".json_encode($request->all())."\r\n\n");
                fwrite($handle, "Errors: ".$errors."\r\n\n");
            }
            fclose($handle);
        }

        return "success";
    }
}
