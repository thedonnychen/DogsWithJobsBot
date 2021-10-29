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
        $tweeted_ids = $_SERVER['TWEETED_IDS'];

        if (file_exists($tweeted_ids)) {
            $new_json = new class{};
            $new_json->ids = [];
            file_put_contents($tweeted_ids, json_encode($new_json));
            echo 'JSON wiped successfully.';
        } else {
            echo 'Error, JSON not reset.';
        }

        return;
    }
}