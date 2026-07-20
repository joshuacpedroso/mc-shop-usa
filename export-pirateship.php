<?php

session_start();

require __DIR__ . '/config.php';
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (empty($_SESSION['admin_logged'])) {
    header('Location: painel.php');
    exit;
}

function paid_only(array $order): bool
{
    $status = strtolower((string)($order['status'] ?? ''));
    $paymentStatus = strtolower((string)($order['payment_status'] ?? ''));

    return $status === 'paid' || $paymentStatus === 'paid';
}

function order_items_text(array $order): string
{
    $parts = [];

    foreach (($order['items'] ?? []) as $item) {

        $qty = (int)($item['qty'] ?? 0);
        $name = $item['name'] ?? 'Produto';
        $size = $item['size'] ?? '';
        $cat = $item['cat'] ?? ($item['category'] ?? '');

        if ($cat === 'shirt') $cat = 'Camisa';
        if ($cat === 'hat' || $cat === 'cap') $cat = 'Boné';

        $txt = $qty . 'x ' . $name;

        if ($cat !== '') {
            $txt .= ' - ' . $cat;
        }

        if ($size !== '') {
            $txt .= ' - Size ' . $size;
        }

        $parts[] = $txt;
    }

    return implode(' | ', $parts);
}

$orderNumber = $_GET['order'] ?? '';
$dateFrom = $_GET['from'] ?? '';
$dateTo = $_GET['to'] ?? '';

if ($orderNumber !== '') {

    $order = load_order($orderNumber);

    $orders = ($order && paid_only($order))
        ? [$order]
        : [];

    $filename = 'pirateship-' .
        preg_replace('/[^A-Z0-9\-_]/i', '', $orderNumber) .
        '.xlsx';

} else {

    $orders = load_orders();

    $orders = array_filter($orders, function ($order) use ($dateFrom, $dateTo) {

        if (!paid_only($order)) {
            return false;
        }

        $created = substr($order['created_at'] ?? '', 0, 10);

        if ($dateFrom && $created < $dateFrom) {
            return false;
        }

        if ($dateTo && $created > $dateTo) {
            return false;
        }

        return true;
    });

    $filename = 'pirateship-pedidos-pagos-' . date('Ymd-His') . '.xlsx';
}

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$headers = [
    'Order Number',
    'Recipient Name',
    'Company',
    'Address 1',
    'Address 2',
    'City',
    'State',
    'ZIP',
    'Country',
    'Email',
    'Phone'
];

$sheet->fromArray($headers, null, 'A1');

$row = 2;

foreach ($orders as $order) {

    $c = $order['customer'] ?? [];

    $customerName = trim((string)($c['name'] ?? ''));
    $orderNumberValue = (string)($order['order_number'] ?? '');
    $itemsText = order_items_text($order);

    $address2 = trim(
        'Order: ' .
        $orderNumberValue .
        ' | ' .
        $itemsText
    );

    $sheet->fromArray([
        $orderNumberValue,
        $customerName,
        $customerName,
        $c['address'] ?? '',
        $address2,
        $c['city'] ?? '',
        $c['state'] ?? '',
        $c['zip'] ?? '',
        'United States',
        $c['email'] ?? '',
        $c['phone'] ?? ''
    ], null, 'A' . $row);

    $row++;
}

while (ob_get_level()) {
    ob_end_clean();
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

exit;