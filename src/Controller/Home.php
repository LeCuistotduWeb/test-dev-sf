<?php

namespace App\Controller;

use App\Service\CrawlerService;
use App\Service\FetchApiService;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Home extends AbstractController
{

    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return Response
     */
    public function index(
        Request $request, 
        FetchApiService $fetchApiService, 
        CrawlerService $crawlerService,
    )
    {    
        $fluxRss = json_decode($fetchApiService->fetchRssFlux('http://www.commitstrip.com/en/feed/'));
        $newsApi = $fetchApiService->fetchJson('https://newsapi.org/v2/top-headlines?country=us&apiKey=c782db1cd730403f88a544b75dc2d7a0');
        $urls = [];
        
        foreach ($fluxRss->channel->item as $item){
            $link = $item->link;
            if(!empty($link)){
                $urls[] =  $link;
            }
        }

        foreach ($newsApi->articles as $i => $item) {
            if(!empty($item->urlToImage)){
                $urls[] = $item->url;
            }
        }
        
        $images = [];
        foreach ($urls as $url){
            if($crawlerService->fetchFirstImage($url)) {
                $images[] = $crawlerService->fetchFirstImage($url);
            }
        }

        return $this->render('default/index.html.twig', ['images'  => array_unique($images)]);
    }
}