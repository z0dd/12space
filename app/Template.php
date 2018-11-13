<?php

namespace App;

/**
 * Class Template
 * @package App
 *
 * @OA\Schema(
 *   schema="Template",
 *   type="object",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/Template"),
 *      @OA\Schema(
 *          required={"name","path"},
 *          @OA\Property(property="id", type="integer"),
 *          @OA\Property(property="name", type="string"),
 *          @OA\Property(property="path", type="string"),
 *          @OA\Property(property="data", type="string"),
 *          @OA\Property(property="created_at", format="timestamp", type="string"),
 *          @OA\Property(property="updated_at", format="timestamp", type="string")
 *      )
 *   }
 * )
 */
class Template extends ModelExtender
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'path',
        'data',
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    /**
     * @return array
     */
    public function rules() :array
    {
        return [
            'name' => 'required|string|min:3|max:250',
            'path' => 'required|string',
            'data' => 'string',
        ];
    }

    /**
     * @return bool
     * @throws \SendGrid\Mail\TypeException
     */
    public function sendNotify(User $user)
    {
        $email = new \SendGrid\Mail\Mail();

        $email->setFrom("test@example.com", "Example User");
        $email->setSubject("Sending with SendGrid is Fun");
        $email->addTo($user->email, $user->full_name);
        $email->addContent(
            "text/plain",
            "test"
        );
        $email->addContent(
            "text/html",
            "<strong>and easy to do anywhere, even with PHP</strong>"
        );
        $sendgrid = new \SendGrid(env('SENDGRID_API_KEY'));

        try {
            $response = $sendgrid->send($email);
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        return $response->statusCode() == 202;
    }
}
