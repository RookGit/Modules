<?

interface CatalogModul
{
    // Активировать плагин
    static function activate();

    // Деактивировать плагин
    static function deactivate();
}

interface CatalogData {

    // Взять данные каталаога по его id
    static function getDataById(integer $id_catalog);

    // Установить данные каталога по его id
    static function setDataById(integer $id_catalog);

    // Добавить каталог
    static function addCatalog(array $arr_data_catalog);

    // Удалить каталог по id
    static function removeCatalogById(integer $id_catalog);

    // Получить список каталогов
    static function getCatalogList();

    // Поиск каталогов по имени
    static function searchCatalogByName(string $name_catalog, int $page, array $params);
}

interface CatalogCheck {

    // Узнать доступ к каталогу по его id
    static function checkAccessById(integer $id_catalog);

    // Проверка корректности массива с данными
    static function checkCorrectData(array $arr_data_catalog);

}

Class Catalog implements CatalogModul, CatalogData
{

    static function activate()
    {
        // TODO: Implement activate() method.
    }

    static function deactivate()
    {
        // TODO: Implement deactivate() method.
    }

    static function getDataById(integer $id_catalog)
    {
        // TODO: Implement getDataById() method.
    }

    static function setDataById(integer $id_catalog)
    {
        // TODO: Implement setDataById() method.
    }

    static function addCatalog(array $arr_data_catalog)
    {
        // TODO: Implement addCatalog() method.
    }

    static function removeCatalogById(integer $id_catalog)
    {
        // TODO: Implement removeCatalogById() method.
    }

    static function getCatalogList()
    {
        // TODO: Implement getCatalogList() method.
    }

    static function searchCatalogByName(string $name_catalog, int $page, array $params)
    {
        // TODO: Implement searchCatalogByName() method.
    }
}

Class CatalogInspector implements CatalogCheck
{

    static function checkAccessById(integer $id_catalog)
    {
        // TODO: Implement checkAccessById() method.
    }

    static function checkCorrectData(array $arr_data_catalog)
    {
        // TODO: Implement checkCorrectData() method.
    }
}