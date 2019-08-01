<?php

namespace App;

use App\Exceptions\ApiException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserToCourse
 * @package App
 */
class UserToCourse extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','course_id','status'
    ];

    /**
     *
     */
    const STATUSES = [
        'not_available' => 0,
        'available' => 1,
        'in_progress' => 2,
        'finished' => 3,
    ];

    /**
     * @param Collection $collection
     * @param int $status
     * @return mixed|null
     */
    public static function findCourseInCollection(Collection $collection, int $status)
    {
        foreach ($collection as $item) {
            if ($item->status == $status)
                return $item;
        }

        return null;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * @return bool
     * @throws ApiException
     */
    public function activate()
    {
        return $this->setStatus(self::STATUSES['in_progress']);
    }

    /**
     * @return bool
     * @throws ApiException
     */
    public function deactivate()
    {
        return $this->setStatus(self::STATUSES['not_available']);
    }

    /**
     * @return bool
     * @throws ApiException
     */
    public function finished()
    {
        return $this->setStatus(self::STATUSES['finished']);
    }

    /**
     * @param int $status
     * @return bool
     * @throws ApiException
     */
    public function setStatus(int $status)
    {
        $this->status = $status;

        if (false == $this->save()) {
            throw new ApiException('Не удалось обновить');
        }

        return true;
    }
}
