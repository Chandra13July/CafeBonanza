<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use setasign\Fpdi\Fpdi;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Report extends Controller
{
    private $orderModel;

    public function __construct()
    {
        $this->orderModel = $this->model('OrderModel');
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['flash_message'] = 'Anda harus login terlebih dahulu!';
            header('Location: ' . BASEURL . '/auth/loginAdmin');
            exit;
        }
    }

    public function index()
    {
        $orders = $this->orderModel->getOrderReport(); // Mengambil laporan order

        $this->view('layout/header');
        $this->view('layout/sidebar');
        $this->view('admin/report', ['orders' => $orders]);
    }

    public function filterReport()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $startDate = trim($_POST['startDate'] ?? '');
            $endDate = trim($_POST['endDate'] ?? '');

            $orders = $this->orderModel->getOrderReport($startDate, $endDate);

            $this->view('layout/header');
            $this->view('layout/sidebar');
            $this->view('admin/report', ['orders' => $orders]);
        }
    }

    public function orderReceipt($orderId)
    {

        if (empty($orderId)) {
            $_SESSION['flash_message'] = 'Order ID tidak ditemukan.';
            header('Location: ' . BASEURL . '/report');
            exit;
        }

        try {
            $receipt = $this->orderModel->getOrderReceipt($orderId);

            // Tampilkan struk di view
            $this->view('layout/header');
            $this->view('layout/sidebar');
            $this->view('admin/receipt', ['receipt' => $receipt]);
        } catch (Exception $e) {
            $_SESSION['flash_message'] = 'Gagal mengambil struk pesanan: ' . $e->getMessage();
            header('Location: ' . BASEURL . '/report');
            exit;
        }
    }

    public function btnEditOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $order = $this->orderModel->findOrderById($_POST['OrderId']);

            if (!isset($_POST['OrderId']) || empty($_POST['OrderId'])) {
                $_SESSION['error'] = "Order ID tidak valid.";
                header("Location: " . BASEURL . "/report/index");
                exit();
            }


            $data = [
                'OrderId' => $_POST['OrderId'],
                'Total' => trim($_POST['total']),
                'Paid' => trim($_POST['paid']),
                'Change' => trim($_POST['change']),
                'PaymentMethod' => trim($_POST['paymentMethod']),
                'Status' => trim($_POST['status']),
            ];

            if ($this->orderModel->editOrder($data)) {
                $_SESSION['success'] = "Order berhasil diperbarui!";
            } else {
                $_SESSION['error'] = "Pembaharuan order gagal.";
            }

            header("Location: " . BASEURL . "/report/index");
            exit();
        }
    }

    public function formatRupiah($amount)
    {
        // Pastikan $amount memiliki nilai numerik, default ke 0 jika null
        $amount = is_numeric($amount) ? (float)$amount : 0;
        return "Rp " . number_format($amount, 0, ',', '.');
    }

    public function exportPdf($startDate = null, $endDate = null)
    {
        $orders = $this->orderModel->getOrderReport($startDate, $endDate);

        usort($orders, function ($a, $b) {
            return strtotime($a['CreatedAt']) - strtotime($b['CreatedAt']);
        });

        $pdf = new Fpdi('L');
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Report Order Cafe Bonanza', 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 12);
        $pdf->SetXY(180, 10);
        $pdf->Cell(0, 10, 'Tanggal Export: ' . date('Y-m-d H:i:s'), 0, 1, 'R');
        $pdf->Ln(10);

        // Header tabel PDF
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(20, 10, 'No', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Customer', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Total', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Paid', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Change', 1, 0, 'C');
        $pdf->Cell(40, 10, 'PaymentMethod', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Status', 1, 0, 'C');
        $pdf->Cell(50, 10, 'CreatedAt', 1, 1, 'C');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 12);
        foreach ($orders as $index => $order) {
            // Format angka menjadi Rupiah
            $total = $this->formatRupiah($order['Total']);
            $paid = $this->formatRupiah($order['Paid']);
            $change = $this->formatRupiah($order['Change']);

            $pdf->Cell(20, 10, $index + 1, 1, 0, 'C');
            $pdf->Cell(40, 10, $order['Customer'], 1, 0, 'C');
            $pdf->Cell(30, 10, $total, 1, 0, 'C');
            $pdf->Cell(30, 10, $paid, 1, 0, 'C');
            $pdf->Cell(30, 10, $change, 1, 0, 'C');
            $pdf->Cell(40, 10, $order['PaymentMethod'], 1, 0, 'C');
            $pdf->Cell(30, 10, $order['Status'], 1, 0, 'C');
            $pdf->Cell(50, 10, $order['CreatedAt'], 1, 0, 'C');
            $pdf->Ln();
        }

        $fileName = 'order_report_' . date('Y-m-d_H-i-s') . '.pdf';
        $filePath = __DIR__ . '/../../public/report/pdf/' . $fileName;

        // Pastikan folder ada dan bisa ditulis
        if (!is_dir(dirname($filePath))) {
            mkdir(dirname($filePath), 0777, true);  // Membuat folder jika belum ada
        }

        $pdf->Output('F', $filePath);

        // Mengirimkan header untuk unduhan PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . filesize($filePath));

        readfile($filePath);

        exit();
    }

    public function exportExcel($startDate = null, $endDate = null)
    {
        $orders = $this->orderModel->getOrderReport($startDate, $endDate);

        usort($orders, function ($a, $b) {
            return strtotime($a['CreatedAt']) - strtotime($b['CreatedAt']);
        });

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Customer');
        $sheet->setCellValue('C1', 'Total');
        $sheet->setCellValue('D1', 'Paid');
        $sheet->setCellValue('E1', 'Change');
        $sheet->setCellValue('F1', 'PaymentMethod');
        $sheet->setCellValue('G1', 'Status');
        $sheet->setCellValue('H1', 'CreatedAt');

        $row = 2;
        foreach ($orders as $index => $order) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $order['Customer']);
            $sheet->setCellValue('C' . $row, $order['Total']);
            $sheet->setCellValue('D' . $row, $order['Paid']);
            $sheet->setCellValue('E' . $row, $order['Change']);
            $sheet->setCellValue('F' . $row, $order['PaymentMethod']);
            $sheet->setCellValue('G' . $row, $order['Status']);
            $sheet->setCellValue('H' . $row, $order['CreatedAt']);

            // Format Total, Paid, Change sebagai mata uang Rp
            $sheet->getStyle('C' . $row)->getNumberFormat()->setFormatCode('Rp #,##0');
            $sheet->getStyle('D' . $row)->getNumberFormat()->setFormatCode('Rp #,##0');
            $sheet->getStyle('E' . $row)->getNumberFormat()->setFormatCode('Rp #,##0');

            $row++;
        }

        foreach (range('A', 'H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        $fileName = 'order_report_' . date('Y-m-d_H-i-s') . '.xlsx';
        $filePath = __DIR__ . '/../../public/report/excel/' . $fileName;

        $writer->save($filePath);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . filesize($filePath));

        readfile($filePath);

        exit();
    }

    public function exportCsv($startDate = null, $endDate = null)
    {
        // Menyalakan output buffering
        ob_start();

        $orders = $this->orderModel->getOrderReport($startDate, $endDate);

        usort($orders, function ($a, $b) {
            return strtotime($a['CreatedAt']) - strtotime($b['CreatedAt']);
        });

        $fileName = 'order_report_' . date('Y-m-d_H-i-s') . '.csv';
        $filePath = __DIR__ . '/../../public/report/csv/' . $fileName;
        $output = fopen($filePath, 'w');

        // Menulis header CSV
        fputcsv($output, ['No', 'Customer', 'Total', 'Paid', 'Change', 'PaymentMethod', 'Status', 'CreatedAt']);

        $no = 1;
        foreach ($orders as $order) {
            // Memeriksa dan memformat nilai jika null atau tidak valid
            $total = isset($order['Total']) && is_numeric($order['Total']) ? 'Rp ' . number_format($order['Total'], 0, ',', '.') : 'Rp 0';
            $paid = isset($order['Paid']) && is_numeric($order['Paid']) ? 'Rp ' . number_format($order['Paid'], 0, ',', '.') : 'Rp 0';
            $change = isset($order['Change']) && is_numeric($order['Change']) ? 'Rp ' . number_format($order['Change'], 0, ',', '.') : 'Rp 0';

            fputcsv($output, [
                $no++,
                $order['Customer'],
                $total,
                $paid,
                $change,
                $order['PaymentMethod'],
                $order['Status'],
                $order['CreatedAt']
            ]);
        }

        fclose($output);

        // Mengirimkan header untuk unduhan CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . filesize($filePath));

        // Membaca file untuk diunduh
        readfile($filePath);

        // Menutup output buffer dan mengirimkan output
        ob_end_flush();

        exit();
    }

    public function exportJson($startDate = null, $endDate = null)
    {
        try {
            date_default_timezone_set('Asia/Jakarta');

            $orders = $this->orderModel->getOrders($startDate, $endDate);

            if (empty($orders)) {
                echo json_encode([
                    'status' => 'failure',
                    'message' => 'No orders found for the given date range.',
                    'data' => [],
                    'metadata' => [
                        'date_exported' => date('Y-m-d H:i:s'),
                    ]
                ]);
                exit();
            }

            $result = [];
            foreach ($orders as $order) {
                $orderId = $order['OrderId'];
                if (!isset($result[$orderId])) {
                    $result[$orderId] = [
                        'OrderId' => $orderId,
                        'Customer' => $order['CustomerUsername'],
                        'Total' => $this->formatRupiah($order['Total']),
                        'Paid' => $this->formatRupiah($order['Paid']),
                        'Change' => $this->formatRupiah($order['Change']),
                        'PaymentMethod' => $order['PaymentMethod'],
                        'Status' => $order['Status'],
                        'CreatedAt' => $order['CreatedAt'],
                        'OrderDetails' => [],
                        'totalPrice' => $this->formatRupiah($order['Total']),
                    ];
                }

                $result[$orderId]['OrderDetails'][] = [
                    'OrderDetailId' => $order['OrderDetailId'],
                    'MenuName' => $order['MenuName'],
                    'Quantity' => $order['Quantity'],
                    'Price' => $this->formatRupiah($order['Price']),
                    'Subtotal' => $this->formatRupiah($order['Subtotal'])
                ];
            }

            $finalResult = [
                'status' => 'success',
                'message' => 'Orders exported successfully.',
                'date_exported' => date('Y-m-d H:i:s'),
                'totalOrders' => count($result),
                'data' => array_values($result),
            ];

            $fileName = 'orders_report_' . date('Y-m-d_H-i-s') . '.json';
            $filePath = __DIR__ . '/../../public/report/json/' . $fileName;

            if (file_put_contents($filePath, json_encode($finalResult, JSON_PRETTY_PRINT))) {
                $jsonData = file_get_contents($filePath);
                header('Content-Type: application/json');
                echo $jsonData;
            } else {
                echo json_encode([
                    'status' => 'failure',
                    'message' => 'Failed to save the file.',
                    'data' => [],
                ]);
            }

            exit();
        } catch (PDOException $e) {
            echo json_encode([
                'status' => 'failure',
                'message' => 'Error executing query: ' . $e->getMessage(),
                'data' => [],
                'metadata' => [
                    'date_exported' => date('Y-m-d H:i:s'),
                ]
            ]);
        }
    }

    public function exportXml($startDate = null, $endDate = null)
    {
        try {
            date_default_timezone_set('Asia/Jakarta');

            $orders = $this->orderModel->getOrders($startDate, $endDate);

            if (empty($orders)) {
                header('Content-Type: application/xml');
                echo "<?xml version='1.0' encoding='UTF-8'?><response><status>failure</status><message>No orders found for the given date range.</message><metadata><date_exported>" . date('Y-m-d H:i:s') . "</date_exported></metadata></response>";
                exit();
            }

            $xml = new SimpleXMLElement('<OrdersExport/>');

            $xml->addChild('status', 'success');
            $xml->addChild('message', 'Orders exported successfully.');
            $xml->addChild('date_exported', date('Y-m-d H:i:s'));

            $ordersNode = $xml->addChild('Orders');
            $orderCount = 0;

            foreach ($orders as $order) {
                $orderId = $order['OrderId'];

                if (!$ordersNode->xpath("Order[OrderId='$orderId']")) {
                    $orderNode = $ordersNode->addChild('Order');
                    $orderNode->addChild('OrderId', $orderId);
                    $orderNode->addChild('Customer', htmlspecialchars($order['CustomerUsername']));
                    $orderNode->addChild('Total', $this->formatRupiah($order['Total']));
                    $orderNode->addChild('Paid', $this->formatRupiah($order['Paid']));
                    $orderNode->addChild('Change', $this->formatRupiah($order['Change']));
                    $orderNode->addChild('PaymentMethod', htmlspecialchars($order['PaymentMethod']));
                    $orderNode->addChild('Status', htmlspecialchars($order['Status']));
                    $orderNode->addChild('CreatedAt', $order['CreatedAt']);

                    $detailsNode = $orderNode->addChild('OrderDetails');
                } else {
                    $orderNode = $ordersNode->xpath("Order[OrderId='$orderId']")[0];
                    $detailsNode = $orderNode->OrderDetails;
                }

                $detailNode = $detailsNode->addChild('OrderDetail');
                $detailNode->addChild('OrderDetailId', $order['OrderDetailId']);
                $detailNode->addChild('MenuName', htmlspecialchars($order['MenuName']));
                $detailNode->addChild('Quantity', $order['Quantity']);
                $detailNode->addChild('Price', $this->formatRupiah($order['Price']));
                $detailNode->addChild('Subtotal', $this->formatRupiah($order['Subtotal']));

                $orderCount++;
            }

            $xml->addChild('totalOrders', $orderCount);

            $fileName = 'orders_report_' . date('Y-m-d_H-i-s') . '.xml';
            $filePath = __DIR__ . '/../../public/report/xml/' . $fileName;

            if ($xml->asXML($filePath)) {
                header('Content-Type: application/xml');
                echo file_get_contents($filePath);
            } else {
                header('Content-Type: application/xml');
                echo "<?xml version='1.0' encoding='UTF-8'?><response><status>failure</status><message>Failed to save the file.</message></response>";
            }

            exit();
        } catch (PDOException $e) {
            header('Content-Type: application/xml');
            echo "<?xml version='1.0' encoding='UTF-8'?><response><status>failure</status><message>Error executing query: " . htmlspecialchars($e->getMessage()) . "</message><metadata><date_exported>" . date('Y-m-d H:i:s') . "</date_exported></metadata></response>";
        }
    }

    public function exportHtml($startDate = null, $endDate = null)
    {
        try {
            date_default_timezone_set('Asia/Jakarta');

            // Ambil data pesanan dan urutkan berdasarkan CreatedAt
            $orders = $this->orderModel->getOrderReport($startDate, $endDate);

            if (empty($orders)) {
                echo "<h1>No orders found for the given date range.</h1>";
                exit();
            }

            // Urutkan berdasarkan CreatedAt, pastikan dalam format yang sesuai
            usort($orders, function ($a, $b) {
                return strtotime($a['CreatedAt']) - strtotime($b['CreatedAt']);
            });

            $html = "<html><head><title>Orders Report</title></head><body>";
            $html .= "<h1>Orders Report</h1>";
            $html .= "<p>Date Exported: " . date('Y-m-d H:i:s') . "</p>";

            $html .= "<table border='1' cellpadding='5' cellspacing='0'>";
            $html .= "<tr><th>No</th><th>Customer</th><th>Total</th><th>Paid</th><th>Change</th><th>Payment Method</th><th>Status</th><th>Created At</th></tr>";

            $no = 1; // Inisialisasi nomor urut
            foreach ($orders as $order) {
                $html .= "<tr>";
                $html .= "<td>{$no}</td>"; // Menampilkan nomor urut
                $html .= "<td>{$order['Customer']}</td>";
                $html .= "<td>" . $this->formatRupiah($order['Total']) . "</td>";
                $html .= "<td>" . $this->formatRupiah($order['Paid']) . "</td>";
                $html .= "<td>" . $this->formatRupiah($order['Change']) . "</td>";
                $html .= "<td>{$order['PaymentMethod']}</td>";
                $html .= "<td>{$order['Status']}</td>";
                $html .= "<td>{$order['CreatedAt']}</td>";
                $html .= "</tr>";
                $no++; // Increment nomor urut
            }

            $html .= "</table></body></html>";

            $fileName = 'orders_report_' . date('Y-m-d_H-i-s') . '.html';
            $filePath = __DIR__ . '/../../public/report/html/' . $fileName;

            if (file_put_contents($filePath, $html)) {
                header('Content-Type: text/html');
                echo file_get_contents($filePath);
            } else {
                echo "<h1>Failed to save the file.</h1>";
            }

            exit();
        } catch (PDOException $e) {
            echo "<h1>Error executing query: " . htmlspecialchars($e->getMessage()) . "</h1>";
        }
    }

    public function exportText($startDate = null, $endDate = null)
    {
        try {
            date_default_timezone_set('Asia/Jakarta');

            $orders = $this->orderModel->getOrders($startDate, $endDate);

            if (empty($orders)) {
                echo "No orders found for the given date range.\n";
                exit();
            }

            // Menyusun header laporan
            $result = "Orders Report\n";
            $result .= "Date Exported: " . date('Y-m-d H:i:s') . "\n\n";
            $result .= sprintf(
                "%-5s %-15s %-15s %-15s %-15s %-20s %-15s %-20s\n",
                "No",
                "Customer",
                "Total",
                "Paid",
                "Change",
                "Payment Method",
                "Status",
                "Created At"
            );

            // Menyusun garis pemisah yang lebih pas
            $result .= str_repeat("-", 130) . "\n";  // Garis pemisah yang lebih pas

            // Menyusun data setiap order
            $no = 1;
            foreach ($orders as $order) {
                $result .= sprintf(
                    "%-5d %-15s %-15s %-15s %-15s %-20s %-15s %-20s\n",
                    $no,
                    $order['CustomerUsername'],
                    $this->formatRupiah($order['Total']),
                    $this->formatRupiah($order['Paid']),
                    $this->formatRupiah($order['Change']),
                    $order['PaymentMethod'],
                    $order['Status'],
                    $order['CreatedAt']
                );
                $no++;
            }

            // Menyimpan file laporan teks
            $fileName = 'orders_report_' . date('Y-m-d_H-i-s') . '.txt';
            $filePath = __DIR__ . '/../../public/report/text/' . $fileName;

            if (file_put_contents($filePath, $result)) {
                header('Content-Type: text/plain');
                echo file_get_contents($filePath);
            } else {
                echo "Failed to save the file.\n";
            }

            exit();
        } catch (PDOException $e) {
            echo "Error executing query: " . $e->getMessage() . "\n";
        }
    }
}
