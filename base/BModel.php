<?php
/**
 * A part of BPhalcon.
 * @author tran.duc.thang
 */

namespace Wataridori\Bphalcon;
use \Phalcon\Mvc\Model\Behavior\Timestampable;
use \Phalcon\Mvc\Model;

/**
 * BModel extends the Model class of Phalcon, with various of features included.
 * When you create a new model, remember to extend from BModel instead of Model to use
 * convenient features of Base Phalcon
 */

class BModel extends Model
{
    /**
     * Attach timestamp behaviour to all model instance
     */
    public function initialize()
    {
        $this->addBehavior(new Timestampable(
            [
                'beforeCreate' => [
                    'field' => 'created_at',
                    'format' => 'Y-m-d H:i:s',
                ],
                'beforeUpdate' => [
                    'field' => 'updated_at',
                    'format' => 'Y-m-d H:i:s',
                ],
            ]
        ));
    }

    /**
     * Get all attributes
     * @return array attributes
     */
    public function getAttributesName()
    {
        return $this->getModelsMetaData()->getAttributes($this);
    }

    /**
     * Return the all the save attributes, which can be use in mass assignment or display in create/update form
     * @return array save attributes
     */
    public function getSaveAttributesName()
    {
        return [];
    }

    /**
     * Define label to each attribute. If an attribute's lable is not defined, the label will be generate by BText::snakeToWords function
     * @return the Label of each attribute, which will be displayed in create/edit form
     */
    public static function getAttributeLabels()
    {
        return [];
    }

    /**
     * Return all save attributes and their values
     * @return array The save attributes and values
     */
    public function getSaveAttributes()
    {
        $attributes = $this->getSaveAttributesName();
        $arr = [];
        foreach($attributes as $att) {
            $arr[$att] = $this->$att;
        }
        return $arr;
    }

    /**
     * Set values to save attributes
     * @param array $params Set values to save attributes
     */
    public function setSaveAttributes($params)
    {
        $this->load($params);
    }

    /**
     * Magic method to use attribute in snake_case
     * For example $ex->a_method is same as $ex->getAMethod()
     * @param string $property The property in snake_case
     * @return mixed Call the method if it exists, otherwise call parent __get() method
     */
    public function __get($property)
    {
        $method = 'get' . Phalcon\Text::camelize($property);
        if (method_exists($this, $method)) {
            return $this->$method();
        }
        parent::__get($property);
    }

    /**
     * Magic method to use attribute in snake_case
     * For example $ex->a_method = $value is same as $ex->setAMethod($value)
     * @param string $property The property in snake_case
     * @param array $params The value that will be passed to set method
     * @return mixed Call the method if it exists, otherwise call parent __set() method
     */
    public function __set($property, $params)
    {
        $method = 'set' . Phalcon\Text::camelize($property);
        if (method_exists($this, $method)) {
            return $this->$method($params);
        }
        parent::__set($property, $params);
    }

    /**
     * Check whether the attribute is save or not
     * @param $att string
     * @return boolean
     */
    public function isSaveAttribute($att)
    {
        $save_attributes = $this->getSaveAttributes();
        return isset($save_attributes[$att]);
    }

    /**
     * Get the label of an attribute. If the label is not defined in the attributeLabels() function,
     * it will be generate be the snakeToWords() function, which will convert a snake_case string to Words
     * with the first letter capitalized.
     * @param $att string
     * @return string
     */
    public static function getAttributeLabel($att)
    {
        $attribute_label = static::getAttributeLabels();
        if (isset($attribute_label[$att])) {
            return $attribute_label[$att];
        }
        return BText::snakeToWords($att, true);
    }

    /**
     * The function to do mass assignment with the inputted attributes. If the attributes is empty,
     * all the save attributes can be changed via mass assignment.
     * @param $params
     * @param array $attributes
     */
    public function load($params, $attributes=[])
    {
        if (!$attributes) {
            $attributes = $this->getSaveAttributesName();
        }
        foreach ($attributes as $att) {
            if (isset($params[$att])) {
                $this->$att = $params[$att];
            }
        }
    }

    /**
    * Get a view link of an instance of BModel.
    * @param string $controller_name The controller which contains action view.
    * By default, the controller name will be the same as Model name with the first character is in lowercase
    * @return string The view link
    */
    public function getViewLink($controller_name='')
    {
        if (!$controller_name) {
            $controller_name = lcfirst(get_class($this));
        }
        return "$controller_name/view/$this->id";
    }
}