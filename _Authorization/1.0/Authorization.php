<?php

class Authorization
{

    public static $name_modul = '_Authorization';

    function __construct()
    {
        // Авторизован ли пользователь?
        $this->auth = 0;

        // Данные пользователя
        $this->data = [];

        // Привилегии пользователя
        $this->privilege = [];
    }

    /**
     * Добавить поля
     *
     * @param array $array - поля
     */
    public function add_field_site(Array $array)
    {
        global $config;

        if ($config['modules'][self::$name_modul]['site_fields'] == null)
            $config['modules'][self::$name_modul]['site_fields'] = [];

        foreach ($array as $item) {
            $config['modules'][self::$name_modul]['site_fields'][] = $item;
        }

        $config['modules'][self::$name_modul]['site_fields']
            = array_unique($config['modules'][self::$name_modul]['site_fields']);
    }

    /**
     * Активация плагина.
     */
    public function activate(): void
    {
        $res = query('
            CREATE TABLE public.users
            (
                id integer NOT NULL DEFAULT nextval(\'users_id_seq\'::regclass),
                login character(250) COLLATE pg_catalog."default" NOT NULL,
                password character(100) COLLATE pg_catalog."default" NOT NULL,
                reset_key character(100) COLLATE pg_catalog."default",
                privilege text[] COLLATE pg_catalog."default",
                CONSTRAINT users_pkey PRIMARY KEY (id)
            )  
        ');

        if ($res['result'] == 0) {
            exit($res['error']);
        }

    }

    /**
     * Авторизоваться
     *
     * @param String $login - логин
     * @param String $password - пароль (без хеша)
     */
    public function auth(string $login, string $password)
    {
        $password_sha1 = sha1($password);

        $res = self::check_has_user($login, $password_sha1);


        if ($res != false) {

            $this->set_data($res);

            $_SESSION['auth_key'] = $this->get_session_key();
            $_SESSION['id'] = $this->data['id'];
        } else {
            $this->log_out();
        }

    }

    // Взять данные пользователя по id
    private function get_data_by_id(int $id)
    {
        $res = query('
        SELECT * FROM users WHERE id = ?
        ', [$id]);

        if ($res['result'] == 1) {
            return $res;
        } else
            return false;
    }

    /**
     * Установить данные в объект
     *
     * @param Array $data - логин
     *
     * */
    private function set_data($data)
    {
        $this->auth = 1;

        $this->privilege =
            explode(
                ',',
                str_replace(['{', '}'], '', $data['data'][0]['privilege'])
            );

        unset($data['data'][0]['privilege']);

        $this->data = $data['data'][0];
    }

    /**
     * Проверить если ли пользователь с бд с таким логином и паролем
     *
     * @param String $login - логин
     * @param String $password_sha1 - пароль (хешированный sha1)
     *
     * @return bool|mixed - 0 - не найден. Array - найден.
     */
    static function check_has_user(string $login, string $password_sha1)
    {

        $res = query('
        SELECT * FROM users WHERE login = ? AND password = ?
        ', [$login, $password_sha1]);

        if ($res['result'] == 1) {
            return $res;
        } else
            return false;
    }

    /**
     * Проверить если ли пользователь с бд с таким логином и паролем
     *
     * @param String $login - логин
     *
     * @return bool|mixed - 0 - не найден. Array - найден.
     */
    static function check_has_user_by_login(string $login)
    {

        $res = query('
        SELECT * FROM users WHERE login = ?
        ', [$login]);

        if ($res['result'] == 1) {
            return $res;
        } else
            return false;
    }

    // Получить sha1 ключ сессии
    private function get_session_key($id = null)
    {
        if ($id == null) $id = @$this->data['id'];

        if (empty($id))
            return false;
        else
            return sha1('user_' . $id);
    }

    /**
     * Авторизован ли пользователь?
     *
     * @return int 0 - не авторизован | 1 - авторизован
     */
    public function check_auth()
    {

        if (
            !empty($_SESSION['auth_key']) &&
            !empty($_SESSION['id']) &&
            $this->get_session_key($_SESSION['id']) == $_SESSION['auth_key']
        ) {

            if (empty($this->data['id']))
                $this->set_data($this->get_data_by_id($_SESSION['id']));

            return 1;
        } else {
            return 0;
        }
    }

    // Выход из аккаунта
    public function log_out()
    {
        if ($this->check_auth()) {

            unset($_SESSION['auth_key']);
            unset($_SESSION['id']);

            $this->auth = 0;
            $this->data = [];
            $this->privilege = [];
        }
    }

    public static function set_reset_key($login)
    {
        $key = sha1(uniqid());
        query('
            UPDATE public.users
            SET reset_key = ? 
            WHERE login = ?;
        ', [$key, $login]);
    }

    static function reg_user($data)
    {
        global $config;

        // Колонки для вставки
        $column = [];

        // Параметры
        $params = [];

        if (
            $data['login'] != null &&
            $data['password'] != null
        ) {

            // Проверяем нет ли такого пользователя в бд
            if(self::check_has_user_by_login($data['login'])['result'] == 0)
            {
                $data['password'] = sha1($data['password']);

                $array_site_fields = $config['modules'][self::$name_modul]['site_fields'];

                array_push($array_site_fields, 'login', 'password');

                foreach ($array_site_fields as $key) {
                    if (!empty($data[$key])) {
                        $column[] = '"' . $key . '"';
                        $val = $data[$key];
                        $params[] = $val;
                    }
                }

                $arr_params = array_fill(0, sizeof($column), '?');
                $sql = 'INSERT INTO users (' . implode(',', $column) . ') 
            VALUES (' . implode(',', $arr_params) . ');';

                $res = query($sql, $params);

                $res['comment'] = 'Success reg user';

                return $res;
            } else
                return [
                    'result' => 0,
                    'error' => 'This login has in data base',
                ];


        } else
            return [
                'result' => 0,
                'error' => 'Parameters password and login can not be null',
            ];
    }
}

$system['user'] = new Authorization();
//$system['user']->auth('admin', 'qweqwe22');

// Проверяем авторизован ли пользователь, если нет, то выход
if ($system['user']->check_auth() == 0)
    $system['user']->log_out();

//Authorization::set_reset_key('admin');

if ($_GET['action'] == 'logout') {
    $system['user']->log_out();
    header('location: /');
}

//pre($system['user']);


