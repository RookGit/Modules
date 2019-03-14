<?

$system['routes'] = [];

interface RouterMethods
{
    // Добавить роут
    public function add(string $rout, string $controller): Router;

    public function __invoke(string $rout, string $controller): Router;

    public function addRequest(string $filter_request): Router;

    static function getRout();
}

Class Router implements RouterMethods
{

    // Последний, добавленный роут
    private $last_rout;

    // Активный контроллер
    static $active_controller;

    // Вернуть активный контроллер
    static function getController()
    {
        return self::$active_controller;
    }

    public function __invoke(string $rout, string $controller): Router
    {
        return $this->add($rout, $controller);
    }

    public function add(string $rout, string $controller): Router
    {
        global $system;

        $this->last_rout = $rout;

        $system['routes'][$rout] = [
            'controller' => $controller
        ];

        return $this;
    }

    public function addRequest(string $filter_request): Router
    {
        $filter_request = mb_strtolower($filter_request);

        global $system;

        switch ($filter_request) {
            case 'get':

                $system['routes'][$this->last_rout] = [
                    'filter_request' => ['get']
                ];
                break;

            case 'post':
                $system['routes'][$this->last_rout] = [
                    'filter_request' => ['post']
                ];
                break;

            case 'request':
                $system['routes'][$this->last_rout] = [
                    'filter_request' => ['request']
                ];
                break;

            case 'post&get':
            case 'get&post':
            $system['routes'][$this->last_rout] = [
                    'filter_request' => ['post', 'get']
                ];
                break;
        }

        return $this;

    }

    static function getRout()
    {
        global $config, $system;

        // TODO: Add filter isset request

        if(
            !empty($system['routes']) &&
            is_array($system['routes']) &&
            array_key_exists(
            $config['url'][0],
            $system['routes'])
        )
        {

            $rout = $system['routes'][$config['url'][0]];


            if(!empty($rout['filter_request']))
            {
                foreach ($rout['filter_request'] as $item)
                {
                    switch ($item)
                    {
                        case 'get':
                            if(empty($_GET))
                                return false;
                            break;

                        case 'post':
                            if(empty($_POST))
                                return false;
                            break;

                        case 'request':
                            if(empty($_REQUEST))
                                return false;
                            break;
                    }
                }
            }


            self::$active_controller = $rout['controller'];

            return true;
        }
        else
            return false;

    }
}

$ROUTER = new Router();

// Example:
// $ROUTER('test', 'url')->addRequest('GET&post');
// $ROUTER('test', 'url')->addRequest('request');
// $ROUTER('test', 'url');
