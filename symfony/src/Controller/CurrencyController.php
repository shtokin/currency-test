<?php

namespace App\Controller;

use App\Service\CurrencyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CurrencyController
 * @package App\Controller
 */
class CurrencyController extends AbstractController
{

    /**
     * @Route("/currency/base/{base}/quote/{quote}", name="currency")
     * @param CurrencyService $currencyService
     * @param string $base
     * @param string $quote
     * @return Response
     */
    public function index(CurrencyService $currencyService, string $base, string $quote): Response
    {
        try {
            $rate = $currencyService->getCurrency($base, $quote);
            return $this->json(['rate' => $rate]);
        } catch (\Exception $ex) {
            return $this->json([
                'message' => 'Can\'t get currency rate',
            ], 500);
        }
    }
}
