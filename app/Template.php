<?php

namespace App;
use SendGrid\Mail\Mail;

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
     * @param User $user
     * @return bool
     * @throws \SendGrid\Mail\TypeException
     */
    public function sendNotify(User $user)
    {
        $templateId = $this->getTemplateId();

        $email = new \SendGrid\Mail\Mail();

        $email->setFrom(
            config('mail.from.address'),
            config('mail.from.name')
        );

        $email->addTo(
            $user->email,
            $user->full_name
        );

        if ($templateId) {
            $email->setTemplateId($templateId);
        }else{
            $email->setSubject("Hello there!");
            $email->addContent(
                "text/html",
                "<strong>Nothing here</strong>"
            );
        }

        $sendgrid = new \SendGrid(env('SENDGRID_API_KEY'));

        try {
            $response = $sendgrid->send($email);
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        return $response->statusCode() == 202;
    }

    /**
     * @return null
     */
    public function getTemplateId()
    {
        $data = $this->unpackData();

        return false == empty($data['template_id'])
            ? $data['template_id']
            : null;
    }

    /**
     * @return null
     */
    public function getTemplateSubstitutions()
    {
        $data = $this->unpackData();

        return false == empty($data['enrichment'])
            ? $data['enrichment']
            : null;
    }

    /**
     * @return mixed
     */
    public function unpackData($data = null)
    {
        if (false == is_null($data)) {
            return json_decode($data,1);
        } else {
            return json_decode($this->data,1);
        }
    }
}
