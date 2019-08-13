<?php

namespace App;

use Carbon\Carbon;

/**
 * Class PassedTest
 * @package App
 *
 * @OA\Schema(
 *   schema="PassedTest",
 *   type="object",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/PassedTest"),
 *      @OA\Schema(
 *          required={"name"},
 *          @OA\Property(property="id", type="integer"),
 *          @OA\Property(property="user_id", type="integer"),
 *          @OA\Property(property="test_id", type="integer"),
 *          @OA\Property(property="question_id", type="integer"),
 *          @OA\Property(property="answer_id", type="integer"),
 *          @OA\Property(property="created_at", format="timestamp", type="string"),
 *          @OA\Property(property="updated_at", format="timestamp", type="string")
 *      )
 *   }
 * )
 */
class PassedTest extends ModelExtender
{
    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'test_id',
        'question_id',
        'answer_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    /**
     * @return array
     */
    public function rules() :array
    {
        return [
            'user_id' => 'required|integer|min:1',
            'test_id' => 'required|integer|min:1',
            'question_id' => 'required|integer|min:1',
            'answer_id' => 'required|integer|min:1',
        ];
    }

    /**
     * @return mixed
     */
    public function nextLesson()
    {
        $tags = $this->user->getPassedTags();

        if ($tags->isEmpty()) {
            // Если нет связи по тегам
            $nextLesson = $this->test->lesson->getNextLesson();

            $nextLesson->published_at = $this->created_at->addDays(config('settings.days_between_lessons'))->format('Y-m-d H:i:s');

            if (empty($nextLesson)) {
                return null;
            }

            return $nextLesson;
        }

        $tag = $tags->first();
        $tag = Tag::find($tag['id']);

        if ($tag->lessons->isEmpty()) {
            return false;
        }

        foreach ($tag->lessons as $lesson) {
            if ($lesson->sort_order > $this->test->lesson->sort_order)
                return $lesson;
        }

        return false;
    }

    /**
     * Метод определяет возможность показать следующий урок после пройденного теста
     * @return bool
     */
    public function nextLessonConditionsSuccess() :bool
    {
        $startDate = $this->created_at->startOfDay();
        $curDate = Carbon::now()->startOfDay();
        $daysToNextLesson = $this->test->lesson->sort_order >= 0 ? $this->test->lesson->sort_order : config('settings.days_between_lessons');

        return $startDate->diffInDays($curDate) >= $daysToNextLesson;
    }
}
