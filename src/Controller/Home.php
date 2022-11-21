<?php

namespace App\Controller;

use App\Service\CrawlerService;
use App\Service\FetchApiService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class Home extends AbstractController
{

    /**
     * @var AdapterInterface
     */
    private $cache;

    public function __construct(AdapterInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return Response
     */
    public function index(
        Request $request, 
        FetchApiService $fetchApiService, 
        CrawlerService $crawlerService,
    ):Response
    {    
        $fluxRss = json_decode($fetchApiService->fetchRssFlux('http://www.commitstrip.com/en/feed/'), true);
        $newsApi = $fetchApiService->fetchJson('https://newsapi.org/v2/top-headlines?country=us&apiKey=c782db1cd730403f88a544b75dc2d7a0');
        
        $urls = array_merge(
            $crawlerService->getUrlsFromArray($fluxRss['channel']['item'], 'link'),
            $crawlerService->getUrlsFromArray($newsApi['articles'], 'url')
        );

        $cacheImages = $this->cache->getItem('hommepageImages');
        if(!$cacheImages->isHit()) {
            $images = array_unique($crawlerService->getImagesFromUrls($urls));
            $cacheImages->set($images);
            $this->cache->save($cacheImages);
        }

        return $this->render('default/index.html.twig', [
            'images'  => $cacheImages->get(),
        ]);
    }
}