<?php
/**
 * Created by PhpStorm.
 * User: z0dd
 * Date: 27.10.2018
 * Time: 10:29
 */

namespace App;

/**
 * Interface ApiModelInterface
 * @package App
 */
interface ApiModelInterface
{
    /**
     * @return mixed
     */
    public function rules() :array ;
}
