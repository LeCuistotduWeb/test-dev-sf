<?php

namespace App\Service;
use Goutte\Client;

class CrawlerService
{
    private $extensions = ['gif','jpg','jpeg','png'];

    /**
     * @param [type] $arr
     * @param string $key
     * @return array
     */
    public function getUrlsFromArray(array $arr, string $key):array
    {
        $urls = [];
        foreach ($arr as $item){
            $url = $item[$key];
            if(!empty($item[$key])){
                $urls[] = $url;
            }
        }
        return $urls;
    }

    /**
     * @param [type] $url
     */
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

    /**
     * @param array $urls
     * @return array
     */
    public function getImagesFromUrls(array $urls) :array
    {
        $images=[];
        foreach ($urls as $url){
            if($this->fetchFirstImage($url)) {
                $images[] = $this->fetchFirstImage($url);
            }
        }
        return $images;
    }
}
