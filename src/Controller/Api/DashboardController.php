<?php

namespace App\Controller\Api;

use App\Service\DashboardService;
use App\Repository\SubscriptionRepository; // Still need this for graphs
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/dashboard')]
class DashboardController extends AbstractController
{
    #[Route('/overview', methods: ['GET'])]
    public function index(
        Request $request,
        DashboardService $dashboardService,
        SubscriptionRepository $subscriptionRepository,
    ): JsonResponse {
        $region = $request->query->get('region');

        return $this->json([
            'executive_summary' => $dashboardService->getExecutiveSummary($region),
            'actions' => $dashboardService->getActionableInsights(),
            'top_services' => $subscriptionRepository->getTopPerformingServices(),
        ]);
    }
}
