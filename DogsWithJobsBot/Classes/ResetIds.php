<?php 

namespace DogsWithJobsBot\Classes;
use Dotenv;

class ResetIds
{
    public function __construct()
    {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $this->NewJson();
    }

        
    /**
     * NewJson
     * Creates a clean JSON file
     * @return void
     */
    public function NewJson()
    {
        $json_file_path = __DIR__ . '/../../' . $_SERVER['TWEETED_IDS'];

        if (file_exists($json_file_path)) {
            $new_json = new class{};
            $new_json->ids = [];
            file_put_contents($json_file_path, json_encode($new_json));
            echo 'JSON wiped successfully.';
        } else {
            echo 'Error, JSON not reset.';
        }

        return;
    }
}