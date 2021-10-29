<?php 
namespace DogsWithJobsBot\Classes;
use DG\Twitter\Twitter;
use DG\Twitter\Exception;
use Dotenv;

class Tweet
{
    const REDDIT_JSON = 'https://reddit.com/r/dogswithjobs/top.json?t=all&sort=month&limit=75';
    const REDDIT_URL = 'https://reddit.com';
    
    public function __construct()
    {   
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $this->sendTweet();
    }
        
    /**
     * sendTweet
     * Connects to Twitter API, sends tweet
     * @return void
     */
    public function sendTweet()
    {
        $twitter            = new Twitter($_SERVER['API_KEY'], $_SERVER['API_SECRET'], $_SERVER['ACCESS_TOKEN'], $_SERVER['ACCESS_TOKEN_SECRET']);
        $tweeted_post_ids   = file_get_contents($_SERVER['TWEETED_IDS']);
        $json_post_ids      = json_decode($tweeted_post_ids);
        $new_post           = $this->getNewPost($json_post_ids->ids);
        $formatted_tweet    = self::REDDIT_URL . $new_post['permalink'];

        try {
            $tweet = $twitter->send($formatted_tweet);
            echo 'Tweet successfully sent.';
        } catch (DG\Twitter\Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
        
        return;
    }
    
    /**
     * getNewPost
     * Returns a randomized single Reddit post array
     * @param  array $json_post_ids
     * @return array
     */
    public function getNewPost($json_post_ids)
    {
        $new_posts  = $this->getNewPosts($json_post_ids);
        $random     = array_rand($new_posts, 1);
        $new_post   = $new_posts[$random];
        
        $this->savePost($json_post_ids, $new_post);

        return $new_post;
    }
    
    /**
     * getNewPosts
     * Returns Reddit posts array that do not exist in the json 'database' file
     * @param  array $json_post_ids
     * @return array
     */
    public function getNewPosts($json_post_ids)
    {   
        $posts = $this->getRedditPosts();
        
        // If not in json 'database', put into possibility for new post
        foreach ($posts as $post) {
            if (!in_array($post['id'], $json_post_ids)) {
                $new_posts[] = $post;
            }
        }
        
        return $new_posts;
    }
    
    /**
     * getRedditPosts
     * Returns formatted Reddit posts array
     * @return array
     */
    public function getRedditPosts()
    {
        $data = json_decode(file_get_contents(self::REDDIT_JSON), true);

        foreach ($data['data']['children'] as $val) {
            $posts[] = $val['data'];
        }

        return $posts;
    }

    /**
     * savePost
     * Saves the tweeted post id to the json 'database
     * @param  array $json_post_ids
     * @param  array $new_post
     * @return void
     */
    public function savePost($json_post_ids, $new_post)
    {
        array_push($json_post_ids, $new_post['id']);
        $updated_post_ids = $json_post_ids;
        $updated_json = new class{};
        $updated_json->ids = $updated_post_ids;
        
        file_put_contents($_SERVER['TWEETED_IDS'], json_encode($updated_json));

        return;
    }
}