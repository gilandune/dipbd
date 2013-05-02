<?php
/**
* CLASS FormValidator
    file: form.validator.inc.php
    version: 0.1
*
* Clase para validar formularios desde php
*
*
* @author Victor Tavares-Almaraz [reuhtte]
* @author http://www.softnetmx.com
* @version 0.1
*/

/*
 * --CONDITIONS

required    The field becomes required.
alpha       The value is restricted to alphabetic chars. 
alphanum    The value is restricted to alphanumeric characters only.
nodigit     The field doesn’t accept digit chars.
digit       The value is restricted to digit (no floating point number) chars, you can pass two arguments (f.e. digit[21,65]) to limit the number between them.  Use -1 as second argument to not set a maximum.
number      The value is restricted to number, including floating point number.
email       The value is restricted to valid email.
image       The value is restricted to images (jpg, jpeg, png, gif, bmp).
phone       The value is restricted to phone chars.
phone_inter The value is restricted to international phone number.
url:        The value is restricted to url.
confirm     The value has to be the same as the one passed in argument. f.e. confirm[password].
differs     The value has to be diferent as the one passed in argument. f.e. differs[user].
length      The value length is restricted by argument (f.e. length[6,10]).  Use -1 as second argument to not set a maximum.
words       The words number is limited by arguments. f.e. words[1,13].  Use -1 as second argument to don’t have a max limit.
not         The words or values that are restricted
*/

/*
 * -- REGEXP

required : /[^.*]/
alpha : /^[a-zñÑáéíóú ._-]+$/i
alphanum : /^[a-zñÑáéíóú0-9 ._-]+$/i
digit : /^[-+]?[0-9]+$/
nodigit : /^[^0-9]+$/
number : /^[-+]?\d*\.?\d+$/
email : /^([a-zA-Z0-9_\.\-\+%])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/
image : /.(jpg|jpeg|png|gif|bmp)$/i
phone : /^[\d\s ().-]+$/, // alternate regex : /^[\d\s ().-]+$/,/^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$/
phone_inter : /^\+{0,1}[0-9 \(\)\.\-]+$/
url : /^(http|https|ftp)\:\/\/[a-z0-9\-\.]+\.[a-z]{2,3}(:[a-z0-9]*)?\/?([a-z0-9\-\._\?\,\'\/\\\+&amp;%\$#\=~])*$/i
 */

//-- error messages
DEFINE('err_REQUIRED', 'campo requerido');
DEFINE('err_ALPHA', 'el campo solo acepta letras');
DEFINE('err_ALPHANUM', 'el campo solo acepta letras y numeros');
DEFINE('err_DIGIT', 'el campo solo acepta numeros enteros');
DEFINE('err_NODIGIT', 'no debe contener digitos');
DEFINE('err_NUMBER', 'el campo solo acepta numeros');
DEFINE('err_EMAIL', 'el campo no es mail');
DEFINE('err_IMAGE', 'el campo no es un archivo de imagen');
DEFINE('err_URL', 'el campo no es una url');

DEFINE('err_DIGITLIMITS','numero fuera del rango permitido');
DEFINE('err_LENGTHLIMITS','fuera del rango de caracteres permitido');
DEFINE('err_WORDSLIMITS','la cantidad de palabras no se encuentra dentro del rango permitido');

DEFINE('err_CONFIRM', 'el campo no concide con el anterior');
DEFINE('err_DIFFERS', 'el campo debe ser diferente a');
//--
/*DEFINE('CORRECT', 0);
DEFINE('WARNING', 1);
DEFINE('ERROR', 2);*/

//-- char between string errors
//DEFINE('_N_','<br>');

class FormValidator
{
    private $id; //string
    private $HTMLelements_keys; //string[]
    private $FORM_content; //THE MATRIX[]
    private $isValid; //Bool

    public function __construct(& $data, $id, $char_separate_errs = NULL, $prefix_target = NULL) {
        $this->isValid = true;

        //initialize form id
        $this->id = $id;

        //Define char to separate error messages strings
        if(!is_null($char_separate_errs))
            DEFINE('_N_',$char_separate_errs);
        else
            DEFINE('_N_',' | ');
        //Define el prefijo que se puede utilizar en caso de que se use el id del elemento como target
        if(!is_null($prefix_target))
            DEFINE('_T_',$prefix_target);
        else
            DEFINE('_T_','err_');

        if(!is_null($data) && !empty($data))
        {
            //initialize array form elements ids
            foreach($data as $id_element => $content)
                $this->HTMLelements_keys[] = $id_element;

            foreach($this->HTMLelements_keys as $index)
            {
                //form elements values
                $this->FORM_content[$index]['value'] = NULL;

                //required fields
                if(isset($data[$index]['required']) && !empty($data[$index]['required']))
                    $this->FORM_content[$index]['required'] = $data[$index]['required'];
                else
                    $this->FORM_content[$index]['required'] = false;

                //validation conditions
                $this->FORM_content[$index]['validate'] = $data[$index]['conditions'];

                //initialize errors
                $this->FORM_content[$index]['error'] = NULL;

                //target message errors
                if(isset($data[$index]['target']) && !empty($data[$index]['target']))
                    $this->FORM_content[$index]['target'] = $data[$index]['target'];
                else
                    $this->FORM_content[$index]['target'] = _T_.$index;
            }
        }

    }

    public function validate($data)
    {
        $this->isValid = true;

        foreach($this->HTMLelements_keys as $index)
            $this->FORM_content[$index]['error'] = NULL;

        //Fill FORM_content with de data form sended
        if(!is_null($data) && !empty($data))
            foreach($this->HTMLelements_keys as $index)
                if(isset($data[$index]))
                    $this->FORM_content[$index]['value'] = $data[$index];

        foreach($this->HTMLelements_keys as $index)
        {
            //Validate Required condition
            if($this->FORM_content[$index]['required'])
                if(is_null($this->FORM_content[$index]['value']) || trim($this->FORM_content[$index]['value']) == '')
                {
                    $this->isValid = false;
                    $this->FORM_content[$index]['error'] = err_REQUIRED._N_;
                }
            //Validate others conditions
            if(isset($this->FORM_content[$index]['validate']) && is_array($this->FORM_content[$index]['validate']))
                foreach($this->FORM_content[$index]['validate'] as $condition)
                {
                    $key = NULL;
                    $first = NULL;
                    $last = NULL;

                    //digit limits obtained
                    if($condition != 'nodigit')
                        if(strpos($condition, 'digit') !== false)//Ambiguity with nodigit
                        {
                            $key = substr($condition, strpos($condition,'[')+1, strpos($condition, ']')-strpos($condition,'[')-1);
                            $first = trim(substr($key,0, strpos($key, ',')),',');
                            $last = trim(substr($key,strpos($key, ',')+1),',');
                            $condition = 'digit';
                        }
                    //length limits obtained
                    if(strpos($condition, 'length') !== false)
                    {
                        $key = substr($condition, strpos($condition,'[')+1, strpos($condition, ']')-strpos($condition,'[')-1);
                        $first = trim(substr($key,0, strpos($key, ',')),',');
                        $last = trim(substr($key,strpos($key, ',')+1),',');
                        $condition = 'length';
                    }
                    //HTML element id to confirm obtained
                    if(strpos($condition, 'confirm') !== false)
                    {
                        $key = substr($condition, strpos($condition,'[')+1, strpos($condition, ']')-strpos($condition,'[')-1);
                        $condition = 'confirm';
                    }
                    //HTML element id to differs obtained
                    if(strpos($condition, 'differs') !== false)
                    {
                        $key = substr($condition, strpos($condition,'[')+1, strpos($condition, ']')-strpos($condition,'[')-1);
                        $condition = 'differs';
                    }
                    //words limits obtained
                    if(strpos($condition, 'words') !== false)
                    {
                        $key = substr($condition, strpos($condition,'[')+1, strpos($condition, ']')-strpos($condition,'[')-1);
                        $first = trim(substr($key,0,strpos($key, ',')),',');
                        $last = trim(substr($key,strpos($key, ',')+1),',');
                        $condition = 'words';
                    }
                    //not array words obtained
                    if(strpos($condition, 'not') !== false)//Ambiguity with nodigit
                    {
                        $key = substr($condition, strpos($condition,'[')+1, strpos($condition, ']')-strpos($condition,'[')-1);
                        $notthis = explode(',',$key);
                        $condition = 'not';
                    }

                    switch ($condition)
                    {
                        case 'alpha':
                            if(!is_null($this->FORM_content[$index]['value']) && trim($this->FORM_content[$index]['value']) !== '')
                                if(!FormValidator::isAlpha($this->FORM_content[$index]['value']))
                                {
                                    $this->isValid = false;
                                    $this->FORM_content[$index]['error'] .= err_ALPHA._N_;
                                }

                            break;

                        case 'alphanum':
                            if(!is_null($this->FORM_content[$index]['value']) && trim($this->FORM_content[$index]['value']) !== '')
                                if(!FormValidator::isAlphanum($this->FORM_content[$index]['value']))
                                {
                                    $this->isValid = false;
                                    $this->FORM_content[$index]['error'] .= err_ALPHANUM._N_;
                                }

                            break;

                        case 'nodigit':
                            if(!is_null($this->FORM_content[$index]['value']) && trim($this->FORM_content[$index]['value']) !== '')
                                if(!FormValidator::noDigit($this->FORM_content[$index]['value']))
                                {
                                    $this->isValid = false;
                                    $this->FORM_content[$index]['error'] .= err_NODIGIT._N_;
                                }

                            break;

                        case 'digit':
                            if(!is_null($this->FORM_content[$index]['value']) && trim($this->FORM_content[$index]['value']) !== '')
                                if(!FormValidator::isDigit($this->FORM_content[$index]['value']))
                                {
                                    $this->isValid = false;
                                    $this->FORM_content[$index]['error'] .= err_DIGIT._N_;
                                }

                            if($this->FORM_content[$index]['value'] < $first || ($this->FORM_content[$index]['value'] > (int)$last && (int)$last !== -1))
                            {
                                if($this->FORM_content[$index]['required'])
                                    $this->isValid = false;
                                $this->FORM_content[$index]['error'] .= err_DIGITLIMITS." [$first,$last]"._N_;
                            }

                            break;

                        case 'number':
                            if(!is_null($this->FORM_content[$index]['value']) && trim($this->FORM_content[$index]['value']) !== '')
                                if(!FormValidator::isNumber($this->FORM_content[$index]['value']))
                                {
                                    $this->isValid = false;
                                    $this->FORM_content[$index]['error'] .= err_NUMBER._N_;
                                }

                            break;

                        case 'email':
                            if(!is_null($this->FORM_content[$index]['value']) && trim($this->FORM_content[$index]['value']) !== '')
                                if(!FormValidator::isEmail($this->FORM_content[$index]['value']))
                                {
                                    $this->isValid = false;
                                    $this->FORM_content[$index]['error'] .= err_EMAIL._N_;
                                }
                            break;

                        case 'image':
                            if(!is_null($this->FORM_content[$index]['value']) && trim($this->FORM_content[$index]['value']) !== '')
                                if(!FormValidator::isImage($this->FORM_content[$index]['value']))
                                {
                                    $this->isValid = false;
                                    $this->FORM_content[$index]['error'] .= err_IMAGE._N_;
                                }

                            break;

                        case 'phone':

                            break;

                        case 'url:':
                            if(!is_null($this->FORM_content[$index]['value']) && trim($this->FORM_content[$index]['value']) !== '')
                                if(!FormValidator::isURL($this->FORM_content[$index]['value']))
                                {
                                    $this->isValid = false;
                                    $this->FORM_content[$index]['error'] .= err_URL._N_;
                                }

                            break;

                        case 'confirm':
                            if(!is_null($this->FORM_content[$index]['value']) && trim($this->FORM_content[$index]['value']) !== '')
                                if(strcmp($this->FORM_content[$index][value], $this->FORM_content[$key]['value']) !== 0)
                                {
                                    $this->isValid = false;
                                    $this->FORM_content[$index]['error'] .= err_CONFIRM._N_;
                                }
                            break;

                        case 'differs':
                            if(!is_null($this->FORM_content[$index]['value']) && trim($this->FORM_content[$index]['value']) !== '')
                                if(strcmp($this->FORM_content[$index][value], $this->FORM_content[$key]['value']) === 0)
                                {
                                    $this->isValid = false;
                                    $this->FORM_content[$index]['error'] .= err_DIFFERS._N_;
                                }

                            break;

                        case 'length':
                            if(!is_null($this->FORM_content[$index]['value']) && trim($this->FORM_content[$index]['value']) !== '')
                                if(strlen($this->FORM_content[$index]['value']) < (int)$first || (strlen($this->FORM_content[$index]['value']) > (int)$last && (int)$last !== -1))
                                {
                                    $this->isValid = false;
                                    $this->FORM_content[$index]['error'] .= err_LENGTHLIMITS." [$first,$last]"._N_;
                                }

                            break;

                        case 'words':
                            if(!is_null($this->FORM_content[$index]['value']) && trim($this->FORM_content[$index]['value']) !== '')
                                if(sizeof(explode(" ",$this->FORM_content[$index]['value'])) < (int)$first || (sizeof(explode(" ", $this->FORM_content[$index]['value'])) > (int)$last && (int)$last !== -1))
                                {
                                    $this->isValid = false;
                                    $this->FORM_content[$index]['error'] .= err_WORDSLIMITS." [$first,$last]"._N_;
                                }
                            
                            break;

                        case 'not':
                            foreach($notthis as $notThisWord)
                            {
                                if($this->FORM_content[$index]['value'] == $notThisWord)
                                {
                                    $this->isValid = false;
                                    $this->FORM_content[$index]['error'] .= 'Campo Requerido';
                                }
                            }
                            break;

                        default:
                            break;
                    }
                }
        }

        return $this->isValid;
    }

    public function getKeys()
    {
        return $this->HTMLelements_keys;
    }

    /*public function getFORM()
    {
        return $this->FORM_content;
    }*/

    public function getErrors($element_id = NULL)
    {
        if(!is_null($element_id))
            return $this->FORM_content[$element_id]['error'];
        else
        {
            $Errors = array();
            
            foreach($this->HTMLelements_keys as $index)
                if(!empty($this->FORM_content[$index]['error']))
                    $Errors[$index] = "$index:&nbsp;{$this->FORM_content[$index]['error']}";
            
            return $Errors;
        }
            
    }

    public function XAJAXerrors($objResponse)
    {
        foreach($this->getErrors() as $index => $content)
        {
            $objResponse->remove($this->getTargets($index));
            if($content)
            {
                $objResponse->insertAfter($index, 'div', $this->getTargets($index));
                $objResponse->assign($this->getTargets($index),'innerHTML',errorMessage($content));
            }
        }
        return $objResponse;
    }

    public function getTargets($element_id = NULL)
    {
        if(!is_null($element_id))
            return $this->FORM_content[$element_id]['target'];
        else
        {
            $Targets = array();
            
            foreach($this->HTMLelements_keys as $index)
                $Targets[$index] = $this->FORM_content[$index]['target'];
            return $Targets;
        }
    }
    //-- validate functions
    
    public static function isAlpha($value)
    {
        return (preg_match('/^[a-zñÑáéíóú ._-]+$/i', $value) == 1) ? true : false;
    }

    public static function isAlphanum($value)
    {
        return (preg_match('/^[a-zñÑáéíóú0-9 ._-]+$/i', $value) == 1) ? true : false;
    }

    public static function isDigit($value)
    {
        return (preg_match('/^[-+]?[0-9]+$/', $value) == 1) ? true : false;
    }

    public static function noDigit($value)
    {
        return (preg_match('/^[^0-9]+$/', $value) == 1) ? true : false;
    }

    public static function isNumber($value)
    {
        return (preg_match('/^[-+]?\d*\.?\d+$/', $value) == 1) ? true : false;
    }

    public static function isEmail($value)
    {
        return (preg_match('/^([a-zA-Z0-9_\.\-\+%])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/', $value) == 1) ? true : false;
    }

    public static function isImage($value)
    {
        return (preg_match('/.(jpg|jpeg|png|gif|bmp)$/i', $value) == 1) ? true : false;
    }

    public static function isURL($value)
    {
        return (preg_match('/^(http|https|ftp)\:\/\/[a-z0-9\-\.]+\.[a-z]{2,3}(:[a-z0-9]*)?\/?([a-z0-9\-\._\?\,\'\/\\\+&amp;%\$#\=~])*$/i', $value) == 1) ? true : false;
    }
    
}

?>
