<?php
/**
 * Created by PhpStorm.
 * User: web
 * Date: 23.02.20
 * Time: 1:18
 */

/**
 * Class NsnVideo
 *
 * @xmlEncoding utf-8
 * @xmlRoot video
 */
class NsnVideo
{
    /**
     * @var string
     * @xmlCDATA
     */
    public $title;


    /**
     * @var NsnVideoNested[]
     * @name description
     */
    public $nested;


    /**
     * @var string
     */
    public $duration;

    /**
     * @var integer
     */
    public $max_quality;


    /**
     * @var integer
     */
    public $main_thumb_index;

}