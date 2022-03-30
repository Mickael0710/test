<?php
namespace App\Controller;
use App\Entity\Company;
use App\Entity\Leader;
use App\Form\CompanyType;
use App\Form\LeaderType;
use App\Utils\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class DefaultController
 * @package App\Controller
 * @Route("/company")
 */
class DefaultController extends AbstractController {

    /**
     * @Route("/new/{name}", name="api_start_wars", defaults={"name": "people"})
     */
    public function new($name, ApiService $apiService) {
        return $this->render("company/new.html.twig", ['name' => $name]);
    }

    /**
     * @Route("/search/{name}", name="api_search_start_wars", methods={"POST"}, defaults={"name": "people"})
     */
    public function search($name, Request $request, ApiService $apiService) {
        $newJsonResponse = new JsonResponse();
        $search = $request->request->get('search', '');
        $uri = $request->request->get('uri', '');
        $template = 'Pas de résultat';
        if ($name && in_array($name, ['vehicles', 'people', 'films', 'starships', 'planets', 'species'])) {
            if ($request->isXmlHttpRequest() && $request->isMethod('POST')) {
                if ($uri) {
                    $apiService->setCustomUri($uri);
                    $results = $apiService->callAPI('GET', '', []);
                } else {
                    $results = $apiService->callAPI('GET', $name, ['search' => $search]);
                }
                if ($results) {
                    $tabResults = json_decode($results, TRUE);
                    if ($tabResults && key_exists('results', $tabResults) && $tabResults['results']) {
                        $template = $this->renderView('company/api-results.html.twig', [
                            'results' => $tabResults['results'],
                            'nextPage' => $tabResults['next'],
                            'previousPage' => $tabResults['previous'],
                            'name' => $name
                        ]);
                    }
                }
            }
        }
        $newJsonResponse->setData(['template' => $template]);
        return $newJsonResponse;
    }

    /**
     * @Route("/details", name="api_details_start_wars", methods={"POST"})
     */
    public function details(Request $request, ApiService $apiService) {
        $newJsonResponse = new JsonResponse();
        $uri = $request->request->get('uri', '');
        $apiService->setCustomUri($uri);
        $results = $apiService->callAPI('', '', []);
        $template = 'Pas de contenu';
        if ($results) {
            $tabResults = json_decode($results, TRUE);
            if ($tabResults) {
                $template = $this->renderView('company/api-details.html.twig', ['results' => $tabResults]);
            }
        }
        $newJsonResponse->setData(['template' => $template]);
        return $newJsonResponse;
    }
}