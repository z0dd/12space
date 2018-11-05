<?php
/**
 * Created by PhpStorm.
 * User: z0dd
 * Date: 27.10.2018
 * Time: 11:46
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ModelExtender
 * @package App
 */
abstract class ModelExtender extends Model implements ApiModelInterface
{
    /**
     * list of relations for present
     *
     * @var array
     */
    protected $presentRelations = [];

    /**
     * @return $this
     */
    public function present()
    {
        if (false == empty($this->presentRelations())) {
            $this->load($this->presentRelations());
        }
        return $this;
    }

    /**
     * @return array
     */
    protected function presentRelations()
    {
        return $this->presentRelations;
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeWithDefaultRelations($query)
    {
        return $query;
    }
}
