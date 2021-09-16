<?php

namespace Importer;

class Importer {
    private array $data;
    private string $full_path;
    private const IMAGE_DIR = "images";
    private const FILE_EXT = '.json';
    private array $dir = [];
    private string $save_path;
    private string $file_name;
    private string $file_path;

    /**
     * @throws \Exception
     */
    public function __construct(string $file_path)
    {
        $this->full_path = $file_path;
        $this->dir = scandir(self::IMAGE_DIR);
        $this->get_file_name();
        $this->get_file_path();
        $this->get_save_path();
        $this->get_data_file();
        if (empty($this->file_name) || empty($this->file_path) || empty($this->save_path) ){
            (new ImporterError())->onInvalidPath();
        }
    }

    public function get_data_file()
    {
        $tool_data = file_get_contents($this->file_path.$this->file_name);
        $this->data = json_decode($tool_data, true);
    }

    protected function get_file_name()
    {
        $path_arr = explode('/' , $this->full_path);
        $this->file_name = array_reduce($path_arr , function (&$memo , $path){
            $ext = self::FILE_EXT;
            preg_match("/{$ext}$/" , $path , $matches);
            if (count($matches)){
                $memo = $path;
            }
            return $memo;
        },"");
    }

    protected function get_file_path()
    {
        $path_arr = explode('/' , $this->full_path);
        $this->file_path = array_reduce($path_arr , function (&$memo , $path){
            $ext = self::FILE_EXT;
            preg_match("/{$ext}$/" , $path , $matches);
            if (count($matches) == 0){
                $memo .= "{$path}/";
            }
            return $memo;
        },"");
    }

    protected function get_save_path()
    {
        $path_arr = explode('/' , $this->full_path);
        $save_ptr = array_search('database',$path_arr);
        $save_path_arr = array_filter($path_arr , function ($key) use ($save_ptr){
            if ($key <= $save_ptr){
                return true;
            }
            return false;
        }, ARRAY_FILTER_USE_KEY);

        $this->save_path = array_reduce($save_path_arr , function ($memo , $path){
            $memo .= "{$path}/";
            return $memo;
        } , "");
    }

    public function import()
    {
        $res = $this->get_images();
        echo "import completed successfully.";
    }

    /**
     * @return array
     */
    protected function get_images() : void
    {
        foreach ($this->dir as $item_dir) {
            foreach ($this->data as $tool) {
                if ($item_dir == $tool['t_name']) {
                    $item_images = scandir(self::IMAGE_DIR . "/{$item_dir}");

                    $image_name = array_reduce($item_images, function ($memo, $image) {
                        if (strlen($memo) > 2) {
                            return $memo;
                        }
                        if (strlen($image) > 3) {
                            $memo = $image;
                        }
                        return $memo;
                    }, "");
                    //Check if the directory already exists.
                    if(!is_dir($this->save_path . self::IMAGE_DIR)){
                        //Directory does not exist, so lets create it.
                        mkdir("{$this->save_path}" . self::IMAGE_DIR , 0755);
                    }

                    $ext = substr($image_name , strpos($image_name ,'.') , strlen($image_name));

                    $copied = copy(self::IMAGE_DIR . "/{$item_dir}/{$image_name}" , "{$this->save_path}" . self::IMAGE_DIR . "/{$tool['t_name']}{$ext}" );

                    if (!$copied){
                        echo "unable to copy $image_name";
                    }
                }
            }
        }
    }
}