<?

$config['routes'] = [];

interface RouterMethods
{
    // Добавить роут
    public function add(string $rout, string $controller): Router;

    public function addRequest(string $filter_request): Router;

    static function getRout();
}

Class Router implements RouterMethods
{

    // Последний, добавленный роут
    private $last_rout;

    public function add(string $rout, string $controller): Router
    {
        global $config;

        $this->last_rout = $rout;

        $config['routes'][$rout] = [
            'controller' => $controller
        ];

        return $this;
    }

    public function addRequest(string $filter_request): Router
    {
       $filter_request = mb_strtolower($filter_request);

       global $config;

       switch ($filter_request)
       {
           case 'get':

               $config['routes'][$this->last_rout] = [
                   'filter_request' => ['get']
               ];
               break;

           case 'post':
               $config['routes'][$this->last_rout] = [
                   'filter_request' => ['post']
               ];
               break;

           case 'post&get':
           case 'get&post':
               $config['routes'][$this->last_rout] = [
                   'filter_request' => ['post', 'get']
               ];
               break;
       }

       return $this;

    }

    static function getRout()
    {
        global $config;

        // TODO: Add filter isset request

        return array_key_exists($config['url'][0], $config['routes']);

    }
}

$router = new Router();

pre($config['routes']);

$router->add('test', 'url')->addRequest('GET');



pre($config['routes']);
