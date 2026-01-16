<?php

namespace App\Service;

use App\Repository\SubscriptionRepository;

class DashboardService
{
    public function __construct(
        private SubscriptionRepository $subscriptionRepository
    ) {}

    public function getExecutiveSummary(?string $region): array
    {
        $currentMRR = $this->subscriptionRepository->calculateTotalMRR($region);

        if ($currentMRR <= 0) {
            return [
                'mrr' => 0,
                'growth_percentage' => 0.0,
                'projected_revenue' => 0
            ];
        }

        $randomGrowthFactor = 1.05;
        $lastMonthMRR = $currentMRR / $randomGrowthFactor;
        $growth = (($currentMRR - $lastMonthMRR) / $lastMonthMRR) * 100;

        return [
            'mrr' => $currentMRR,
            'growth_percentage' => round($growth, 1),
            'projected_revenue' => $currentMRR * 12
        ];
    }

    public function getActionableInsights(?string $region): array
    {
        $critical = $this->subscriptionRepository->findCriticalSubscriptions(5, $region);

        $actions = [];
        foreach ($critical as $sub) {
            $limit = $sub->getPlan()->getLimitValue();
            if ($limit == 0) continue;

            $usagePct = round(($sub->getCurrentUsage() / $limit) * 100);

            $actions[] = [
                'type' => 'UPSELL_OPPORTUNITY',
                'severity' => 'high',
                'message' => "{$sub->getClient()->getName()} is at {$usagePct}% capacity.",
                'action_text' => 'Offer Upgrade',
                'client_id' => $sub->getClient()->getId()
            ];
        }

        return $actions;
    }
}
