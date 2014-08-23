<?php
/**
 * A part of BPhalcon.
 * @author tran.duc.thang
 */

namespace Wataridori\Bphalcon;

use \Phalcon\Mvc\Controller;

/**
 * BController extends the Controller class of Phalcon, with various of features included.
 * When you create a new Controller, remember to extend from BController instead of Controller to use
 * convenient features of Base Phalcon
 */

class BController extends Controller
{
    /**
     * @var array $errors Store all errors from submitted form
     */
    public $errors = [];

    /**
     * The the default values to attributes
     * @param BModel $model An instance of BModel
     * @param array $attributes The list of attributes that will be set default. If the $attributes is empty,
     * all the save attributes will be set default
     */
    public function setDefault($model, $attributes=[])
    {
        if (!$attributes) {
            $attributes = $model->getSaveAttributesName();
        }
        foreach ($attributes as $att) {
            $this->tag->setDefault($att, $model->$att);
        }
    }

    /**
     * A function to use dispatcher forward in a shorter way.
     * @param string $uri . For example 'example/edit'
     * @param array $params . The params which will be passed to the dispatcher->forward() function
     * @return mixed
     */
    protected function forward($uri, $params=[]){
        $uriParts = explode('/', $uri);
        return $this->dispatcher->forward(
            [
                'controller' => $uriParts[0],
                'action' => isset($uriParts[1]) ? $uriParts[1] : 'index',
                'params' => $params,
            ]
        );
    }
}