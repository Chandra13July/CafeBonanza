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
            $pdf->Cell(20, 10, $index + 1, 1, 0, 'C');  
            $pdf->Cell(40, 10, $order['Customer'], 1, 0, 'C');
            $pdf->Cell(30, 10, 'Rp ' . number_format($order['Total'], 0, ',', '.'), 1, 0, 'C'); 
            $pdf->Cell(30, 10, 'Rp ' . number_format($order['Paid'], 0, ',', '.'), 1, 0, 'C');
            $pdf->Cell(30, 10, 'Rp ' . number_format($order['Change'], 0, ',', '.'), 1, 0, 'C'); 
            $pdf->Cell(40, 10, $order['PaymentMethod'], 1, 0, 'C');
            $pdf->Cell(30, 10, $order['Status'], 1, 0, 'C');
            $pdf->Cell(50, 10, $order['CreatedAt'], 1, 0, 'C');
            $pdf->Ln();
        }
    
        $fileName = 'order_report_' . date('Y-m-d_H-i-s') . '.pdf';
        $filePath = __DIR__ . '/../../public/report/pdf/' . $fileName;
    
        $pdf->Output('F', $filePath);
    
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
        $orders = $this->orderModel->getOrderReport($startDate, $endDate);

        usort($orders, function ($a, $b) {
            return strtotime($a['CreatedAt']) - strtotime($b['CreatedAt']);
        });

        $fileName = 'order_report_' . date('Y-m-d_H-i-s') . '.csv';

        $filePath = __DIR__ . '/../../public/report/csv/' . $fileName;

        $output = fopen($filePath, 'w');

        fputcsv($output, ['No', 'Customer', 'Total', 'Paid', 'Change', 'PaymentMethod', 'Status', 'CreatedAt']);

        $no = 1;
        foreach ($orders as $order) {
            $total = 'Rp ' . number_format($order['Total'], 0, ',', '.');
            $paid = 'Rp ' . number_format($order['Paid'], 0, ',', '.');
            $change = 'Rp ' . number_format($order['Change'], 0, ',', '.');

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

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . filesize($filePath));

        readfile($filePath);

        exit();
    }
}
