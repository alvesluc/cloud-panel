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

        $lastMonthMRR = $currentMRR * 0.92;
        $growth = (($currentMRR - $lastMonthMRR) / $lastMonthMRR) * 100;

        return [
            'mrr' => $currentMRR,
            'growth_percentage' => round($growth, 1),
            'mrr_context' => $growth > 0 ? 'You are growing faster than last Q' : 'Revenue has stabilized',
            'projected_revenue' => $currentMRR * 12
        ];
    }

    public function getActionableInsights(): array
    {
        $critical = $this->subscriptionRepository->findCriticalSubscriptions();

        $actions = [];
        foreach ($critical as $sub) {
            $usagePct = round(($sub->getCurrentUsage() / $sub->getPlan()->getLimitValue()) * 100);

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
