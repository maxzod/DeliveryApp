<?php


namespace App\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use App\Entity\Order;
use App\Repository\OrderRepository;
use DatePeriod;
#[Route('/admin')]
class DashboardController extends AbstractController
{

    public function __construct(private ChartBuilderInterface $chartBuilder)
    {
    }
    #[Route('/', name: 'admin')]
    public function index(): Response
    {
        $chart = $this->getChart(new \DateTimeImmutable('+1 day'), new \DateTimeImmutable('-29 day'));
        return $this->render('admin/dashboard.html.twig', [
            'chart' => $chart
        ]);
    }

    private function getChart(\DateTimeImmutable $startDay,\DateTimeImmutable $endDay): Chart
    {
        /**
         * @var OrderRepository $repo
         */
        $repo = $this->getDoctrine()->getRepository(Order::class);
        $qb = $repo->createQueryBuilder('o');

        $orders = $qb->where('o.createdAt between :end and :begin')
            ->andWhere('o.status <> :status')
            ->select("SUM(o.price) as total, DATE_FORMAT(o.createdAt, '%m-%d') as cd")
            ->groupBy('cd')
            ->setParameter('begin', $startDay)
            ->setParameter('end', $endDay)
            ->setParameter('status', Order::STATUS_WAITING)
            ->getQuery()
            ->getArrayResult();

        $ordersArray = [];
        foreach ($orders as $order)
        {
            $ordersArray[$order['cd']] = $order;
        }
        $daysRange = new DatePeriod(
            $endDay,
            new \DateInterval('P1D'),
            $startDay
        );
        $labels = [];
        $data = [];
        foreach ($daysRange as $i => $day)
        {
            $labels[] = $date = $day->format('m-d');
            $data[] = array_key_exists($date, $ordersArray) ? $ordersArray[$date]['total'] : 0;
        }
        $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'مبيعات اخر 30 يوم',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $data,
                ],
            ],
        ]);
        return $chart;
    }
}