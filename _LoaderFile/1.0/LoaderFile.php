<?

interface LoaderFile {

    // Активация
    static function activate();

    // Проверить существует ли файл
    static function is_file_has(string $str_path_file);

    // Установить папку для загрузки
    public function set_folder_load(string $str_path_for_load);

    // Установить поля файла
    public function set_file_field(array $arr_file_field);

    // Загрузить массив $_FILES
    public function load_files(array $arr_data_file);

    // Удалить файл
    public function delete_files(int $id_file);

    // Обновить данные файла
    public function update_file(int $id, array $arr_data_file);
}
