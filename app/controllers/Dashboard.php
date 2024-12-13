<?php

class Dashboard extends Controller
{
    private $EmployeeModel;
    private $CustomerModel;
    private $MenuModel;
    private $OrderModel;

    public function __construct()
    {
        $this->MenuModel = $this->model('MenuModel');
        $this->EmployeeModel = $this->model('EmployeeModel');
        $this->CustomerModel = $this->model('CustomerModel');
        $this->OrderModel = $this->model('OrderModel');

        if (!isset($_SESSION['user_id'])) {
            $_SESSION['flash_message'] = 'You must log in first!';
            header('Location: ' . BASEURL . '/auth/loginAdmin');
            exit;
        }
    }

    public function index()
    {
        // Target yang bisa diubah dari pengaturan
        $targets = [
            'menu' => 25,
            'customer' => 50,
            'order' => 100,
            'profit' => 5000000
        ];

        // Mengambil data statistik
        $totalMenu = $this->MenuModel->getTotalMenu();
        $menuPercentage = ($totalMenu / $targets['menu']) * 100;

        $totalCustomer = $this->CustomerModel->getTotalCustomer();
        $customerPercentage = ($totalCustomer / $targets['customer']) * 100;

        $totalOrders = $this->OrderModel->getTotalOrders();
        $orderPercentage = ($totalOrders / $targets['order']) * 100;

        $currentMonthProfit = $this->OrderModel->getCurrentMonthCompletedProfit();
        $profitPercentage = ($currentMonthProfit / $targets['profit']) * 100;

        $monthlyOrders = $this->OrderModel->getMonthlyTotalOrdersWithZero(date('Y'));

        $monthlyCompletedProfit1 = $this->OrderModel->getMonthlyCompletedProfit1(date('Y'));

        $popularMenu = $this->MenuModel->getPopularMenu();

        $stockStatusMenu = $this->MenuModel->getStockStatus();  // Mengambil data stok habis atau hampir habis

        $popularCustomer = $this->CustomerModel->getPopularCustomer();

        // Memanggil fungsi untuk mendapatkan jumlah pesanan berdasarkan hari dalam minggu
        $weeklyOrders = $this->OrderModel->getWeeklyOrderCount();

        // Memanggil fungsi untuk mendapatkan jumlah pesanan per jam
        $hourlyOrders = $this->OrderModel->getHourlyOrderCount();

        // Memanggil fungsi untuk mendapatkan jumlah pesanan berdasarkan minggu dalam bulan
        $month = date('m'); // Mendapatkan bulan saat ini
        $year = date('Y');  // Mendapatkan tahun saat ini
        $monthlyOrdersByWeek = $this->OrderModel->getMonthlyOrderCountByWeek($month, $year);

        // Menambahkan label minggu (Minggu 1, Minggu 2, dst.)
        foreach ($monthlyOrdersByWeek as $index => &$weekData) {
            $weekData['weekLabel'] = 'Minggu ' . ($index + 1);  // Menambahkan label "Minggu 1", "Minggu 2", dst.
        }

        // Nama hari dalam minggu
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        // Nama bulan
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        // Jam 18:00 hingga 02:00
        $hoursOfDay = [
            18 => '18:00',
            19 => '19:00',
            20 => '20:00',
            21 => '21:00',
            22 => '22:00',
            23 => '23:00',
            0  => '00:00',
            1  => '01:00',
            2  => '02:00'
        ];

        $data = [
            'totalMenu' => $totalMenu,
            'menuPercentage' => $menuPercentage,
            'totalCustomer' => $totalCustomer,
            'customerPercentage' => $customerPercentage,
            'totalOrders' => $totalOrders,
            'orderPercentage' => $orderPercentage,
            'currentMonthProfit' => $currentMonthProfit,
            'profitPercentage' => $profitPercentage,
            'targets' => $targets,
            'monthlyOrders' => $monthlyOrders,
            'monthlyCompletedProfit1' => $monthlyCompletedProfit1,
            'popularMenu' => $popularMenu,
            'popularCustomer' => $popularCustomer,
            'stockStatusMenu' => $stockStatusMenu,
            'weeklyOrders' => $weeklyOrders,  // Menambahkan data pesanan mingguan
            'hourlyOrders' => $hourlyOrders,  // Menambahkan data pesanan per jam
            'hoursOfDay' => $hoursOfDay,  // Menambahkan array jam 18:00 hingga 02:00
            'daysOfWeek' => $daysOfWeek,  // Menambahkan array hari dalam minggu
            'months' => $months,
            'monthlyOrdersByWeek' => $monthlyOrdersByWeek, // Data pesanan per minggu
        ];

        // Menampilkan view
        $this->view('layout/header', $data);
        $this->view('layout/sidebar', $data);
        $this->view('admin/dashboard', $data);
    }
}
