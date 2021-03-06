<?php

/**
 * Created by PhpStorm.
 * User: milos
 * Date: 29/05/17
 * Time: 21:51
 */
namespace Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Rule
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    public $params;

    public function __construct($values) {
        if(empty($values) || !isset($values['value'])) {
            throw new \Exception('You must specify a rule name.');
        }
        $this->name = $values['value'];
        if(isset($values['params'])) {
            $this->params = explode(',', $values['params']);
        }
    }
}