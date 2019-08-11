<?php
/**
 * Created by PhpStorm.
 * User: z0dd
 * Date: 18.06.2019
 * Time: 16:35
 */

namespace App;


use Illuminate\Database\Eloquent\Model;
use SendGrid\Response;

/**
 * Class SendgridNotification
 *
 * @package App
 */
class SendgridNotification extends Model
{
    /**
     * @var string
     */
    protected $table = 'sendgrid_notifications';
    /**
     * @var array
     */
    protected $fillable = ['email','x_message_id','status','user_id','template_id'];

    /**
     * @param Response $response
     * @param Template $template
     * @param User     $user
     *
     * @throws \Exception
     */
    public static function processSendGridRespose(Response $response, Template $template, User $user)
    {
        $messageId = null;

        foreach ($response->headers() as $header) {
            $headerPattern = '/X-Message-Id: */i';
            if (preg_match($headerPattern, $header)) {
                $messageId = preg_replace($headerPattern,'',$header);
            }
        }

        if (is_null($messageId))
            throw new \Exception('Sendgrid error: wrong headers',500);

        $notification = new SendgridNotification([
            'user_id' => $user->id,
            'template_id' => $template->id,
            'email' => $user->email,
            'x_message_id' => $messageId,
            'status' => 'sended',
        ]);

        $notification->save();
    }
}
