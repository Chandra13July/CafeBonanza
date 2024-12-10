<?php
require_once __DIR__ . '/../../vendor/autoload.php';  // Pastikan path ini benar

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
            // Ensure data is sanitized and not null
            $startDate = trim($_POST['startDate'] ?? '');
            $endDate = trim($_POST['endDate'] ?? '');

            // Call the updated method to fetch filtered data based on date range
            $orders = $this->orderModel->getOrderReport($startDate, $endDate);

            // Pass the filtered data to the view
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

    // Metode untuk ekspor laporan ke PDF
    public function exportPdf($startDate = null, $endDate = null)
    {
        // Ambil data pesanan dari model
        $orders = $this->orderModel->getOrderReport($startDate, $endDate);

        // Inisialisasi objek FPDF
        $pdf = new Fpdi();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 12);

        // Menambahkan header kolom ke PDF
        $pdf->Cell(30, 10, 'OrderId', 1);
        $pdf->Cell(50, 10, 'Customer', 1);
        $pdf->Cell(30, 10, 'Total', 1);
        $pdf->Cell(30, 10, 'Paid', 1);
        $pdf->Cell(30, 10, 'Change', 1);
        $pdf->Cell(30, 10, 'PaymentMethod', 1);
        $pdf->Cell(30, 10, 'Status', 1);
        $pdf->Cell(30, 10, 'CreatedAt', 1);
        $pdf->Ln();

        // Menambahkan data pesanan ke PDF
        foreach ($orders as $order) {
            $pdf->Cell(30, 10, $order['OrderId'], 1);
            $pdf->Cell(50, 10, $order['Customer'], 1);
            $pdf->Cell(30, 10, $order['Total'], 1);
            $pdf->Cell(30, 10, $order['Paid'], 1);
            $pdf->Cell(30, 10, $order['Change'], 1);
            $pdf->Cell(30, 10, $order['PaymentMethod'], 1);
            $pdf->Cell(30, 10, $order['Status'], 1);
            $pdf->Cell(30, 10, $order['CreatedAt'], 1);
            $pdf->Ln();
        }

        // Output PDF ke browser
        $pdf->Output();
        exit();
    }

    // Metode untuk ekspor laporan ke Excel
    public function exportExcel($startDate = null, $endDate = null)
    {
        // Ambil data pesanan dari model
        $orders = $this->orderModel->getOrderReport($startDate, $endDate);

        // Buat objek Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header kolom
        $sheet->setCellValue('A1', 'OrderId');
        $sheet->setCellValue('B1', 'Customer');
        $sheet->setCellValue('C1', 'Total');
        $sheet->setCellValue('D1', 'Paid');
        $sheet->setCellValue('E1', 'Change');
        $sheet->setCellValue('F1', 'PaymentMethod');
        $sheet->setCellValue('G1', 'Status');
        $sheet->setCellValue('H1', 'CreatedAt');

        // Menambahkan data pesanan ke sheet
        $row = 2;
        foreach ($orders as $order) {
            $sheet->setCellValue('A' . $row, $order['OrderId']);
            $sheet->setCellValue('B' . $row, $order['Customer']);
            $sheet->setCellValue('C' . $row, $order['Total']);
            $sheet->setCellValue('D' . $row, $order['Paid']);
            $sheet->setCellValue('E' . $row, $order['Change']);
            $sheet->setCellValue('F' . $row, $order['PaymentMethod']);
            $sheet->setCellValue('G' . $row, $order['Status']);
            $sheet->setCellValue('H' . $row, $order['CreatedAt']);
            $row++;
        }

        // Buat writer untuk menyimpan file Excel
        $writer = new Xlsx($spreadsheet);

        // Set header untuk file Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="order_report.xlsx"');
        header('Cache-Control: max-age=0');

        // Simpan dan output file Excel
        $writer->save('php://output');
        exit();
    }

    // Metode untuk ekspor laporan ke CSV
    public function exportCsv($startDate = null, $endDate = null)
    {
        // Ambil data pesanan dari model
        $orders = $this->orderModel->getOrderReport($startDate, $endDate);

        // Set header untuk file CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="order_report.csv"');

        // Buka output untuk menulis CSV
        $output = fopen('php://output', 'w');

        // Menulis header kolom
        fputcsv($output, ['OrderId', 'Customer', 'Total', 'Paid', 'Change', 'PaymentMethod', 'Status', 'CreatedAt']);

        // Menulis data pesanan ke CSV
        foreach ($orders as $order) {
            fputcsv($output, [
                $order['OrderId'],
                $order['Customer'],
                $order['Total'],
                $order['Paid'],
                $order['Change'],
                $order['PaymentMethod'],
                $order['Status'],
                $order['CreatedAt']
            ]);
        }

        // Tutup file output
        fclose($output);
        exit();
    }
}
