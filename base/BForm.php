<?php
/**
 * A part of BPhalcon
 * @author tran.duc.thang
 */
namespace Wataridori\Bphalcon;

use \Phalcon\Tag;

/**
 * BForm helps you to create form tags base on Model and Attributes.
 * Each Base Form Tag will contains Label, Input and Error Messages.
 *
 * @property array $errors
 * @property array $magic_methods
 * @property BModel $model
 */

class BForm
{
    /**
     * Store errors that occured when save an instance
     * @var array $errors Format ['attribute' => 'Message']
     * Example ['name' => 'name is required']
     */
    public $errors = [];

    /**
     * Accept the following methods as magic methods. All the params will be passed to the same method of Phalcon\Tag
     * @var array $magic_methods
     */
    public static $magic_methods = [
        'textField',
        'numericField',
        'emailField',
        'passwordField',
        'hiddenField',
        'textArea',
        'colorField',
        'rangeField',
        'emailField',
        'dateField',
        'dateTimeField',
        'dateTimeLocalField',
        'monthField',
        'timeField',
        'weekField',
        'searchField',
        'telField',
        'urlField',
        'fileField',
    ];

    /**
     * An instance of BModel
     * @var BModel $model
     */
    public $model;

    /**
     * Input a BModel instance and errors occured when the instance is saved
     * @param BModel $model
     * @param array $errors
     */
    public function __construct($model, $errors = [])
    {
        $this->model = $model;
        if (!$errors) {
            $errors = $model->getMessages();
        }

        if ($errors) {
            foreach ($errors as $error) {
                $this->errors[$error->getField()] = $error->getMessage();
            }
        }
    }

    /**
     * Start BForm
     * @param string $link The url that form data will be summited to
     * @param array $params
     * @return string The result returned by function Tag::form()
     */
    public function start($link, $params=[])
    {
        array_unshift($params, $link);
        if (!isset($params['method'])) {
            $params['method'] = 'post';
        }
        $params['role'] = 'form';
        return Tag::form($params);
    }

    /**
     * End BForm
     * @return string The result returned by function Tag::endForm()
     */
    public function end()
    {
        return Tag::endForm();
    }

    /**
     * Start a default container for a label, an input, and an error span
     * @param string $default_class The default class is 'form-group'. This is a class of bootstrap
     * @param string $default_tag The default class is 'div'
     * @return string
     */
    public function startDefault($default_class='form-group', $default_tag='div')
    {
        return Tag::tagHtml($default_tag, ['class' => $default_class]);
    }

    /**
     * End a default container for a label, an input, and an error span
     * @param string $default_tag The default class is 'div'
     * @return string
     */
    public function endDefault($default_tag='div')
    {
        return Tag::tagHtmlClose($default_tag);
    }

    /**
     * Display attribute name
     * @param string $attribute
     * @return string
     */
    public function label($attribute)
    {
        return Tag::tagHtml('label', ['class' => 'pull-left control-label']) . $this->model->getAttributeLabel($attribute) . Tag::tagHtmlClose('label', true);
    }

    /**
     * Check whether the inputed attibute has errors or not
     * @param string $attribute
     * @return bool
     */
    public function hasError($attribute)
    {

        if (isset($this->errors[$attribute])) {
            return true;
        }
        return false;
    }

    /**
     * Get the error belongs to the inputed attributes
     * @param string $attribute
     * @return string|null
     */
    public function getError($attribute)
    {
        return isset($this->errors[$attribute]) ? $this->errors[$attribute] : null;
    }

    /**
     * Create error span. Bootstrap style
     * @param string $attribute
     * @return string
     */
    public function createErorr($attribute)
    {
        return '<span class="help-block">' . $this->getError($attribute) . '</span>';
    }

    /**
     * Magic method
     * Apply Bootstrap style to the tag generated by the method of Phalcon\Tag
     * @param string $name The method name
     * @param array $args The params that will be passed to the method of Phalcon\Tag
     * @return string
     */
    public function __call($name, $args)
    {
        if(in_array($name, static::$magic_methods)) {
            $attribute = $args[0];
            $params = isset($args[1]) ? $args[1] : [];
            if (!isset($params['class'])) {
                $class = 'form-control';
            } else {
                $class = 'form-control ' . $params['class'];
            }
            $params['class'] = $class;
            array_unshift($params, $attribute);
            $input = Tag::$name($params);
            if ($this->hasError($attribute)) {
                $div_class = 'form-group has-error';
                $error = $this->createErorr($attribute);
            } else {
                $div_class = 'form-group';
                $error = '';
            }
            return $this->startDefault($div_class) . $this->label($attribute) . $input . $error . $this->endDefault();
        } else {
            $this->$name($args);
        }
    }

    /**
     * Create checkfield with bootstrap style
     * @param string $attribute
     * @param array $params
     * @param string $text
     * @return string
     */
    public function checkField($attribute, $params=[], $text='')
    {
        array_unshift($params, $attribute);
        $input = Tag::checkField($params);
        return $this->startDefault('checkbox') . "<label class='pull-left control-label'> $input  $text </label>" . $this->endDefault();
    }

    /**
     * Create checkfield with bootstrap style
     * @param string $text
     * @param array $params
     * @return string
     */
    public function submitButton($text, $params=[])
    {
        if (!isset($params['class'])) {
            $params['class'] = 'btn btn-primary';
        }
        array_unshift($params, $text);
        return Tag::submitButton($params);
    }

    /**
     * Create static select with bootstrap style
     * @param string|array $params The attribute or an array that contain the attribute
     * @param array $data The data of the select
     * @return string
     */
    public function selectStatic($params, $data=[])
    {
        if (!is_array($params)) {
            $attribute = $params;
            $params = [];
            $params[] = $attribute;
            $params['class'] = 'form-control';
        } else{
            $attribute = $params[0];
            if (!isset($params['class'])) {
                $params['class'] = 'form-control';
            } else {
                $params['class'] .= ' form-control';
            }
        }
        if ($this->hasError($attribute)) {
            $div_class = 'form-group has-error';
            $error = $this->createErorr($attribute);
        } else {
            $div_class = 'form-group';
            $error = '';
        }
        return $this->startDefault($div_class) . $this->label($attribute) . Tag::selectStatic($params, $data) . $error . $this->endDefault();
    }

    /**
     * Create select with bootstrap style
     * @param array $data
     * @return string
     */
    public function select($data)
    {
        $params = array_shift($data);
        if (!is_array($params)) {
            $attribute = $params;
            $params = [];
            $params[] = $attribute;
            $params['class'] = 'form-control';
        } else{
            $attribute = $params[0];
            if (!isset($params['class'])) {
                $params['class'] = 'form-control';
            } else {
                $params['class'] .= ' form-control';
            }
        }
        array_unshift($data, $params);
        if ($this->hasError($attribute)) {
            $div_class = 'form-group has-error';
            $error = $this->createErorr($attribute);
        } else {
            $div_class = 'form-group';
            $error = '';
        }
        return $this->startDefault($div_class) . $this->label($attribute) . Tag::select($data) . $error . $this->endDefault();
    }

    /**
     * Just pass the params to Phalcon\Tag::hiddenField()
     * @param string $attribute
     * @param array $params
     * @return string
     */
    public function hiddenField($attribute, $params=[])
    {
        array_unshift($params, $attribute);
        return Tag::hiddenField($params);
    }
}