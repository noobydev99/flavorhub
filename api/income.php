<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/autoload.php';

use FlavorHub\DataAccess\Database;
use FlavorHub\DataAccess\OrderDAO;

// Optional filters
$fromDate = trim($_GET['from_date'] ?? ''); // YYYY-MM-DD
$toDate   = trim($_GET['to_date'] ?? '');
$payment  = trim($_GET['payment_method'] ?? '');
$status   = trim($_GET['status'] ?? '');
$search   = trim(strtolower($_GET['search'] ?? ''));

try {
    $db = Database::getConnection();
    $orderDAO = new OrderDAO($db);

    $orders = $orderDAO->getAll();

    // Transform orders into income-like records
    $records = array_map(function($o) {
        $items = [];
        if (!empty($o['items']) && is_array($o['items'])) {
            foreach ($o['items'] as $it) {
                $items[] = [
                    'name' => $it['name'],
                    'qty'  => (int)($it['quantity'] ?? $it['qty'] ?? 1),
                    'price'=> (float)($it['unit_price'] ?? $it['price'] ?? 0.0)
                ];
            }
        }

        // Map order status to incomeStatus
        $incomeStatus = 'Pending';
        $orderStatus = $o['status'] ?? '';
        if (strcasecmp($orderStatus, 'Delivered') === 0) {
            $incomeStatus = 'Completed';
        } elseif (stripos($orderStatus, 'cancel') !== false) {
            $incomeStatus = 'Cancelled';
        }

        return [
            'incomeId' => 'INC-' . $o['id'],
            'orderId' => $o['order_id'] ?? ('ORD-' . $o['id']),
            'customerName' => $o['customer_name'] ?? '',
            'date' => date('Y-m-d', strtotime($o['created_at'] ?? 'now')),
            'paymentMethod' => $o['payment_method'] ?? '',
            'amount' => (float)($o['total'] ?? 0.0),
            'orderStatus' => $orderStatus,
            'incomeStatus' => $incomeStatus,
            'items' => $items,
            'tax' => (float)($o['tax'] ?? 0.0),
            'serviceCharge' => (float)($o['delivery_fee'] ?? 0.0)
        ];
    }, $orders);

    // Apply filters
    $filtered = array_filter($records, function($r) use ($fromDate, $toDate, $payment, $status, $search) {
        if ($fromDate && $r['date'] < $fromDate) return false;
        if ($toDate && $r['date'] > $toDate) return false;
        if ($payment && strcasecmp($r['paymentMethod'], $payment) !== 0) return false;
        if ($status && strcasecmp($r['incomeStatus'], $status) !== 0) return false;
        if ($search) {
            $q = $search;
            if (stripos(strtolower($r['customerName']), $q) === false &&
                stripos(strtolower($r['orderId']), $q) === false &&
                stripos(strtolower($r['incomeId']), $q) === false) {
                return false;
            }
        }
        return true;
    });

    // Reindex
    $filtered = array_values($filtered);

    // Calculate summary aggregates from filtered set
    $today = date('Y-m-d');
    $currentMonthPrefix = date('Y-m');

    $summary = [
        'todayIncome' => 0.0,
        'monthlyIncome' => 0.0,
        'totalIncome' => 0.0,
        'completedOrders' => 0,
        'monthlySums' => [], // e.g. [ 'May' => x, 'Jun' => y, ... ]
        'paymentTotals' => [ 'Cash' => 0.0, 'Card' => 0.0, 'Online Payment' => 0.0 ]
    ];

    foreach ($filtered as $r) {
        if ($r['incomeStatus'] === 'Completed') {
            $summary['totalIncome'] += $r['amount'];
        }
        if ($r['date'] === $today && $r['incomeStatus'] === 'Completed') {
            $summary['todayIncome'] += $r['amount'];
        }
        if (strpos($r['date'], $currentMonthPrefix) === 0 && $r['incomeStatus'] === 'Completed') {
            $summary['monthlyIncome'] += $r['amount'];
        }
        if ($r['orderStatus'] === 'Delivered') $summary['completedOrders']++;

        // Monthly breakdown (short month name)
        $m = date('M', strtotime($r['date']));
        if (!isset($summary['monthlySums'][$m])) $summary['monthlySums'][$m] = 0.0;
        if ($r['incomeStatus'] === 'Completed') $summary['monthlySums'][$m] += $r['amount'];

        // Payment totals
        $pm = $r['paymentMethod'] ?? '';
        if (isset($summary['paymentTotals'][$pm])) {
            $summary['paymentTotals'][$pm] += $r['amount'];
        }
    }

    // Pagination
    $page = max(1, (int)($_GET['page'] ?? 1));
    $pageSize = max(1, (int)($_GET['page_size'] ?? 5));
    $totalCount = count($filtered);
    $offset = ($page - 1) * $pageSize;
    $paged = array_slice($filtered, $offset, $pageSize);

    echo json_encode([
        'data' => $paged,
        'total' => $totalCount,
        'summary' => $summary
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
