<?php

namespace App\Service;
use Goutte\Client;

class CrawlerService
{
    private $extensions = ['gif','jpg','jpeg','png'];

    public function fetchFirstImage($url) 
    {
        $client = new Client();
        $crawler = $client->request('GET', $url);
        $image = $crawler->filter('body img')->eq(0);
        if(count($image)>0){
            $imageUrl = $image->attr('src');
            if(!empty($imageUrl)) {
                $ext = strtolower(pathinfo($imageUrl, PATHINFO_EXTENSION));
                if (in_array($ext, $this->extensions)) {
                    return $imageUrl;
                }   
            }
        }
    }
}
