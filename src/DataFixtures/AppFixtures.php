<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Client;
use App\Entity\Plan;
use App\Entity\Service;
use App\Entity\Subscription;
use App\Enum\SubscriptionStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = [];
        $categoriesNames = ['E-mail & Colaboração', 'Cloud Computing', 'Backup & Recovery', 'Outros Serviços'];
        foreach ($categoriesNames as $name) {
            $category = new Category();
            $category->setName($name);

            $manager->persist($category);
            $categories[$name] = $category;
        }

        $services = [];
        $serviceData = [
            ['name' => 'Skybox', 'category' => 'E-mail & Colaboração', 'metricUnit' => 'GB'],
            ['name' => 'Skyoffice', 'category' => 'E-mail & Colaboração', 'metricUnit' => 'user'],
            ['name' => 'E-mail Registrado', 'category' => 'E-mail & Colaboração', 'metricUnit' => 'unit'],
            ['name' => 'SMTP', 'category' => 'E-mail & Colaboração', 'metricUnit' => 'unit'],
            ['name' => 'Simple Storage', 'category' => 'Cloud Computing', 'metricUnit' => 'GB'],
            ['name' => 'NFS as a Service', 'category' => 'Cloud Computing', 'metricUnit' => 'GB'],
            ['name' => 'GPU Cloud for AI', 'category' => 'Cloud Computing', 'metricUnit' => 'hour'],
            ['name' => 'Backup em Nuvem', 'category' => 'Backup & Recovery', 'metricUnit' => 'GB'],
            ['name' => 'Cloud VBR', 'category' => 'Backup & Recovery', 'metricUnit' => 'instance'],
            ['name' => 'Microsoft 365', 'category' => 'Outros Serviços', 'metricUnit' => 'user'],
            ['name' => 'Revenda White Label por conta', 'category' => 'Outros Serviços', 'metricUnit' => 'user'],
            ['name' => 'Revenda White Label por licença', 'category' => 'Outros Serviços', 'metricUnit' => 'instance'],
            ['name' => 'Revenda White Label por tier', 'category' => 'Outros Serviços', 'metricUnit' => 'subscription'],
        ];

        foreach ($serviceData as $sData) {
            $service = new Service();
            $service->setName($sData['name']);
            $service->setCategory($categories[$sData['category']]);
            $service->setMetricUnit($sData['metricUnit']);

            $manager->persist($service);
            $services[$sData['name']] = $service;
        }

        $plans = [];
        $tiers = [
            ['suffix' => 'Starter', 'priceMultiplier' => 1, 'limitMultiplier' => 1],
            ['suffix' => 'Business', 'priceMultiplier' => 3.5, 'limitMultiplier' => 5],
            ['suffix' => 'Enterprise', 'priceMultiplier' => 10, 'limitMultiplier' => 20],
        ];
        $baseValues = [
            'Skybox' => ['price' => 25.00, 'limit' => 50],
            'Skyoffice' => ['price' => 40.00, 'limit' => 1],
            'E-mail Registrado' => ['price' => 50.00, 'limit' => 50],
            'SMTP' => ['price' => 100.00, 'limit' => 5000],
            'Simple Storage' => ['price' => 30.00, 'limit' => 100],
            'NFS as a Service' => ['price' => 150.00, 'limit' => 250],
            'GPU Cloud for AI' => ['price' => 200.00, 'limit' => 10],
            'Backup em Nuvem' => ['price' => 45.00, 'limit' => 100],
            'Cloud VBR' => ['price' => 80.00, 'limit' => 1],
            'Microsoft 365' => ['price' => 35.00, 'limit' => 1],
            'Revenda White Label por conta' => ['price' => 300.00, 'limit' => 10],
            'Revenda White Label por licença' => ['price' => 500.00, 'limit' => 5],
            'Revenda White Label por tier' => ['price' => 1000.00, 'limit' => 1],
        ];

        foreach ($baseValues as $serviceName => $base) {
            foreach ($tiers as $tier) {
                $plan = new Plan();
                $plan->setService($services[$serviceName]);
                $plan->setName($serviceName . ' ' . $tier['suffix']);
                $plan->setPrice($base['price'] * $tier['priceMultiplier']);
                $plan->setLimitValue($base['limit'] * $tier['limitMultiplier']);

                $manager->persist($plan);
                $plans[] = $plan;
            }
        }

        $clients = [
            ['name' => 'Logística Brasil', 'region' => 'SP'],
            ['name' => 'Rio Oil & Gas', 'region' => 'RJ'],
            ['name' => 'Agro Tech', 'region' => 'SP'],
            ['name' => 'Mineração Geral', 'region' => 'MG'],
            ['name' => 'Cerrado Grãos', 'region' => 'MT'],
            ['name' => 'Amazon Forest Products', 'region' => 'AM'],
            ['name' => 'Nordeste Solar', 'region' => 'BA'],
            ['name' => 'Porto Digital', 'region' => 'PE'],
            ['name' => 'Sul Malhas', 'region' => 'RS'],
            ['name' => 'Pantanal Ecotur', 'region' => 'MS'],
            ['name' => 'Inova Curitiba', 'region' => 'PR'],
            ['name' => 'Brasília Gov-Tech', 'region' => 'DF'],
            ['name' => 'Vale do Aço', 'region' => 'ES'],
            ['name' => 'Goiás Fertilizantes', 'region' => 'GO'],
            ['name' => 'Santa Catarina Logística', 'region' => 'SC'],
            ['name' => 'Teresina Tech', 'region' => 'PI'],
            ['name' => 'Fortaleza Têxtil', 'region' => 'CE'],
            ['name' => 'Belém Frutas Exóticas', 'region' => 'PA'],
            ['name' => 'Pampa Pecuária', 'region' => 'RS'],
            ['name' => 'Recife Soft', 'region' => 'PE'],
            ['name' => 'Manaus Eletrônicos', 'region' => 'AM'],
            ['name' => 'Natal Turismo', 'region' => 'RN'],
            ['name' => 'Maranhão Alimentos', 'region' => 'MA'],
            ['name' => 'Paraíba Calçados', 'region' => 'PB'],
            ['name' => 'Sergipe Petróleo', 'region' => 'SE'],
            ['name' => 'Alagoas Química', 'region' => 'AL'],
            ['name' => 'Tocantins Bioenergia', 'region' => 'TO'],
            ['name' => 'Acre Borrachas', 'region' => 'AC'],
            ['name' => 'Rondônia Madeira', 'region' => 'RO'],
            ['name' => 'Roraima Minérios', 'region' => 'RR'],
            ['name' => 'Amapá Pesca', 'region' => 'AP'],
            ['name' => 'Campinas Inovação', 'region' => 'SP'],
            ['name' => 'Santos Portuária', 'region' => 'SP'],
            ['name' => 'Belo Horizonte Modas', 'region' => 'MG'],
            ['name' => 'Uberlândia Log', 'region' => 'MG'],
            ['name' => 'Joinville Fundição', 'region' => 'SC'],
            ['name' => 'Blumenau Software', 'region' => 'SC'],
            ['name' => 'Caxias Metalúrgica', 'region' => 'RS'],
            ['name' => 'Pelotas Doces', 'region' => 'RS'],
            ['name' => 'Londrina Agro', 'region' => 'PR'],
            ['name' => 'Maringá Fertilizantes', 'region' => 'PR'],
            ['name' => 'Ribeirão Preto Cana', 'region' => 'SP'],
            ['name' => 'Sorocaba Industrial', 'region' => 'SP'],
            ['name' => 'Vitória Marítima', 'region' => 'ES'],
            ['name' => 'Goiânia Moda Mix', 'region' => 'GO'],
            ['name' => 'Cuiabá Boi Gordo', 'region' => 'MT'],
            ['name' => 'Campo Grande Grãos', 'region' => 'MS'],
            ['name' => 'Maceió Hotelaria', 'region' => 'AL'],
            ['name' => 'Aracaju Automotiva', 'region' => 'SE'],
            ['name' => 'São Luís Porto', 'region' => 'MA'],
        ];

        foreach ($clients as $clientData) {
            $client = new Client();
            $client->setName($clientData['name']);
            $client->setRegion($clientData['region']);

            $manager->persist($client);

            $subscription = new Subscription();
            $subscription->setClient($client);

            $subscription->setPlan($plans[array_rand($plans)]);

            $allStatuses = SubscriptionStatus::cases();
            $subscription->setStatus($allStatuses[array_rand($allStatuses)]);

            $limit = $subscription->getPlan()->getLimitValue();
            $usage = rand(floor($limit * 0.1), floor($limit * 1.1));
            $subscription->setCurrentUsage($usage);

            $manager->persist($subscription);
        }

        $manager->flush();
    }
}
