<?

function phone_number($sPhone)
{
    return preg_replace('/^(\d{4})(\d{3})(\d+)$/iu', '+7 ($1) $2-$3', $sPhone);
}


function num2word($num, $words)
{
    $num = $num % 100;

    if ($num > 19) $num = $num % 10;


    switch ($num) {
        case 1: {
            return ($words[0]);
        }
        case 2:
        case 3:
        case 4: {
            return ($words[1]);
        }
        default: {
            return ($words[2]);
        }
    }
}


Class validManager
{
    static function checkEmpty($element, $returnFalseWord = false, $returnTrueWord = true)
    {
        return empty($element) ? $returnTrueWord : $returnFalseWord;
    }

    static function validString($element, $type, $length = 300)
    {
        switch ($type) {
            case 'string':
                return strlen(trim($element)) > 0;
                break;

            case 'email':
                return filter_var($element, FILTER_VALIDATE_EMAIL) == false ? false : true;
                break;

            case 'phone':
                $result = preg_match('/((8|\+7)-?)?\(?\d{3,5}\)?-?\d{1}-?\d{1}-?\d{1}-?\d{1}-?\d{1}((-?\d{1})?-?\d{1})?/', $element);
                return $result == 1 ? true : false;
                break;

            case 'boolStr':
                return $element == 'true' ? true : false;
                break;

            case 'bool':
                return $element == true || $element == 'true' || $element == 1 ? true : false;
                break;

            case 'length20':
                return strlen($element) <= 20 && strlen($element) > 0 ? true : false;
                break;

            case 'length50':
                return strlen($element) <= 50 && strlen($element) > 0 ? true : false;
                break;

            case 'length100':
                return strlen($element) <= 100 && strlen($element) > 0 ? true : false;
                break;

            case 'length300':
                return strlen($element) <= 300 && strlen($element) > 0 ? true : false;
                break;

            case 'length2000':
                return strlen($element) <= 2000 && strlen($element) > 0 ? true : false;
                break;

            case 'length':
                return strlen($element) <= $length && strlen($element) > 0 ? true : false;
                break;

            case 'passport_seria':
                return strlen(str_replace(' ', '', trim($element))) == 4 ? true : false;
                break;

            case 'passport_num':
                return strlen(str_replace(' ', '', trim($element))) == 6 ? true : false;
                break;

            case 'is_num':
                return strlen($element) > 0 && is_numeric($element) ? true : false;
                break;

            case 'inn':
                return strlen($element) == 10 && is_numeric($element) ? true : false;
                break;

            case 'ogrn':
                return strlen($element) == 13 && is_numeric($element) ? true : false;
                break;

            case 'rus':
                return strlen($element) > 0 && preg_match('/[0-9а-яА-ЯёЁ]+/', $element) ? true : false;
                break;

            case 'en':
                return strlen($element) > 0 && preg_match('/[0-9a-zA-Z]+/', $element) ? true : false;
                break;

            case 'empty':
                return empty($element) ? false : true;
                break;

            default:
                return false;
                break;
        }
    }

    // Проверка группы значений на валидацию
    /*
    *
    * $result = validManager::checkGroup(
    * array(
    * 'my_email' => array('email' => 'Email введен не правильно', 'test@ya.ru'),
    * 'my_email2' => array('is_num' => 'Email введен не правильно2', 'test@ya'),
    * 'my_email3' => array('is_num' => 'test@ya.ru'),
    * array('is_num' => '123'),
    * array('is_num' => '1f23'),
    * array('is_num' => '12f3'),
    * array('is_num' => '1232'),
    * ),
    * true
    * );
    */
    static function checkGroup($arr_group, $key_name = false, $check_explode_filter = false)
    {
        $error = array();

        foreach ($arr_group as $k => $v) {

            $code_filter = $k;

            if (is_array($v)) {

                if (sizeof($v) == 2) {

                    // Код имени, который возвращаем в случае не прохождения валидации
                    $value_check = array_pop($v);

                    $filter = array_keys($v)[0];

                    $filter_explode = 0;

                    if ($check_explode_filter) {
                        $filter_explode = explode('#', $filter);
                    }


                    // Проверяем надо ли нам проверять несколько условий
                    if (sizeof($filter_explode) > 1) {

                        $i = 0;
                        foreach ($filter_explode as $filter_item) {
                            $error_filter = $v[$filter][$i];

                            if (self::validString($value_check, $filter_item) == false) {
                                if ($key_name) {
                                    $error[$code_filter] = $error_filter;
                                    break;
                                } else {
                                    $error[] =
                                        array($code_filter => $error_filter);
                                    break;
                                }
                            }

                            $i++;
                        }
                    } else {

                        $error_filter = $v[$filter][0];

                        if (self::validString($value_check, $filter) == false) {
                            if ($key_name) {
                                $error[$code_filter] = $error_filter;
                            } else {
                                $error[] = array($code_filter => $error_filter);
                            }
                        }
                    }


                } else {
                    $filter = array_keys($v)[0];
                    $value_check = $v[$filter];

                    if (self::validString($value_check, $filter) == false) {
                        if ($key_name) {
                            $error[$code_filter] = 'error';
                        } else {
                            $error[] = $code_filter;
                        }
                    }
                }

            } else {
                $error[] = '------> Some value in parameters this function is now array! *_* it\'s bad!';
            }
        }

        if (empty($error))
            return true;
        else
            return $error;
    }
}


class strManager
{
// Обрезает строку, если она длиннее $cutAfterChar и вставляет в конце символы $strEnd
    static function cutString($string, $cutAfterChar, $strEnd = '...', $deleteTags = false)
    {
        return $deleteTags ? mb_strimwidth(strip_tags($string), 0, $cutAfterChar, $strEnd, "UTF-8") : mb_strimwidth($string, 0, $cutAfterChar, $strEnd, "UTF-8");
    }

// Делит числа по триадам 1000 -> 1 000, 10000000 -> 10 000 000
    static function rank1000($integer)
    {
        return number_format($integer, 0, '', ' ');
    }
}


$config['scripts_modules'][] = $module_path . 'functions.js';
?>