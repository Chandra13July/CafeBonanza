<?php

class OrderModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getTotalOrders()
    {
        $query = "SELECT COUNT(*) as totalOrders FROM `order`";

        $stmt = $this->db->prepare($query);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['totalOrders'];
    }

    public function getMonthlyCompletedProfit($month, $year)
    {
        $query = "
        SELECT SUM(Total) as completedProfit
        FROM `order`
        WHERE MONTH(CreatedAt) = :month 
          AND YEAR(CreatedAt) = :year 
          AND Status = 'Completed'
    ";

        $stmt = $this->db->prepare($query);

        $stmt->bindValue(':month', $month, PDO::PARAM_INT);
        $stmt->bindValue(':year', $year, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['completedProfit'] ?? 0;
    }

    public function getCurrentMonthCompletedProfit()
    {
        $currentMonth = date('n');
        $currentYear = date('Y');

        return $this->getMonthlyCompletedProfit($currentMonth, $currentYear);
    }

    public function getOrderReport($startDate = null, $endDate = null)
    {
        $query = "
        SELECT 
            o.OrderId,
            c.Username AS Customer,
            o.Total,
            o.Paid,
            o.Change,
            o.PaymentMethod,
            o.Status,
            o.CreatedAt
        FROM `order` o
        JOIN `customer` c ON o.CustomerId = c.CustomerId
        WHERE 1=1
    ";

        if ($startDate && $endDate) {
            $query .= " AND o.CreatedAt BETWEEN :startDate AND :endDate";
        }

        $query .= " ORDER BY o.CreatedAt DESC";

        $stmt = $this->db->prepare($query);

        if ($startDate && $endDate) {
            $stmt->bindValue(':startDate', $startDate, PDO::PARAM_STR);
            $stmt->bindValue(':endDate', $endDate, PDO::PARAM_STR);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderReceipt($orderId)
    {
        $queryOrder = "
    SELECT 
        o.OrderId,
        c.Username AS Customer,
        o.Total,
        o.Paid,
        o.Change,
        o.PaymentMethod,
        o.Status,
        o.CreatedAt
    FROM `order` o
    JOIN `customer` c ON o.CustomerId = c.CustomerId
    WHERE o.OrderId = :orderId
    ";

        $queryOrderDetails = "
    SELECT 
        d.MenuId,
        m.MenuName,
        d.Quantity,
        d.Price,
        d.Subtotal
    FROM `orderdetail` d
    JOIN `menu` m ON d.MenuId = m.MenuId
    WHERE d.OrderId = :orderId
    ";

        $stmtOrder = $this->db->prepare($queryOrder);
        $stmtOrder->bindValue(':orderId', $orderId, PDO::PARAM_INT);
        $stmtOrder->execute();
        $order = $stmtOrder->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            throw new Exception("Order dengan ID $orderId tidak ditemukan.");
        }

        $stmtOrderDetails = $this->db->prepare($queryOrderDetails);
        $stmtOrderDetails->bindValue(':orderId', $orderId, PDO::PARAM_INT);
        $stmtOrderDetails->execute();
        $orderDetails = $stmtOrderDetails->fetchAll(PDO::FETCH_ASSOC);

        $receipt = [
            'OrderId' => $order['OrderId'],
            'Customer' => $order['Customer'],
            'Total' => $order['Total'],
            'Paid' => $order['Paid'],
            'Change' => $order['Change'],
            'PaymentMethod' => $order['PaymentMethod'],
            'Status' => $order['Status'],
            'CreatedAt' => $order['CreatedAt'],
            'Items' => $orderDetails
        ];

        return $receipt;
    }

    public function checkout($customerId, $paymentMethod, $orderDetails)
    {
        if (!is_array($orderDetails) || empty($orderDetails)) {
            throw new Exception("Order details are missing or invalid");
        }

        $this->db->beginTransaction();

        try {
            $status = 'pending';
            $queryOrder = "
                INSERT INTO `order` (CustomerId, Total, PaymentMethod, Status)
                VALUES (:customerId, :total, :paymentMethod, :status)
            ";

            $stmtOrder = $this->db->prepare($queryOrder);
            $stmtOrder->bindValue(':customerId', $customerId, PDO::PARAM_INT);
            $stmtOrder->bindValue(':total', 0, PDO::PARAM_STR);
            $stmtOrder->bindValue(':paymentMethod', $paymentMethod, PDO::PARAM_STR);
            $stmtOrder->bindValue(':status', $status, PDO::PARAM_STR);  // Bind status

            $stmtOrder->execute();
            $orderId = $this->db->lastInsertId();
            $queryOrderDetails = "
                INSERT INTO `orderdetail` (OrderId, MenuId, Quantity, Price, Subtotal)
                VALUES (:orderId, :menuId, :quantity, :price, :subtotal)
            ";


            $total = 0;
            foreach ($orderDetails as $item) {
                $cart = $this->db->prepare("SELECT * FROM cart JOIN 
            menu m ON cart.MenuId = m.MenuId WHERE CartId = $item");
                $cart->execute();
                $data = $cart->fetch();
                $subTotal = $data['Quantity'] *  $data['Price'];
                $total += $subTotal;
                $stmtOrderDetails = $this->db->prepare($queryOrderDetails);
                $stmtOrderDetails->bindValue(':orderId', $orderId, PDO::PARAM_INT);
                $stmtOrderDetails->bindValue(':menuId', $data['MenuId'], PDO::PARAM_INT);
                $stmtOrderDetails->bindValue(':quantity', $data['Quantity'], PDO::PARAM_INT);
                $stmtOrderDetails->bindValue(':price', $data['Price'], PDO::PARAM_STR);
                $stmtOrderDetails->bindValue(':subtotal', $subTotal, PDO::PARAM_STR);
                $stmtOrderDetails->execute();
            }

            $query = "UPDATE `order` SET total = '$total' WHERE OrderId = $orderId";
            $cart = $this->db->prepare($query);
            $cart->execute();
            $this->db->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw new Exception("Checkout failed: " . $e->getMessage());
        }
    }

    public function getOrderById($orderId)
    {
        $query = "SELECT * FROM `order` WHERE OrderId = :orderId";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':orderId', $orderId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findOrderById($id)
    {
        $this->db->query('SELECT * FROM `order` WHERE OrderId = :OrderId');
        $this->db->bind(':OrderId', $id, PDO::PARAM_INT);
        return $this->db->single();
    }

    public function editOrder($data)
    {
        $query = "
        UPDATE `order`
        SET Total = :total, Paid = :paid, 
            `Change` = :change, PaymentMethod = :paymentMethod, 
            Status = :status
        WHERE OrderId = :orderId
    ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':orderId', $data['OrderId'], PDO::PARAM_INT);
        $stmt->bindValue(':total', $data['Total'], PDO::PARAM_STR);
        $stmt->bindValue(':paid', $data['Paid'], PDO::PARAM_STR);
        $stmt->bindValue(':change', $data['Change'], PDO::PARAM_STR);
        $stmt->bindValue(':paymentMethod', $data['PaymentMethod'], PDO::PARAM_STR);
        $stmt->bindValue(':status', $data['Status'], PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function getMonthlyTotalOrdersWithZero($year)
    {
        $monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        $query = "
    SELECT 
        months.month,
        COALESCE(COUNT(o.OrderId), 0) AS totalOrders
    FROM 
        (SELECT 1 AS month UNION ALL 
         SELECT 2 UNION ALL 
         SELECT 3 UNION ALL 
         SELECT 4 UNION ALL 
         SELECT 5 UNION ALL 
         SELECT 6 UNION ALL 
         SELECT 7 UNION ALL 
         SELECT 8 UNION ALL 
         SELECT 9 UNION ALL 
         SELECT 10 UNION ALL 
         SELECT 11 UNION ALL 
         SELECT 12) AS months
    LEFT JOIN `order` o 
        ON MONTH(o.CreatedAt) = months.month
        AND YEAR(o.CreatedAt) = :year
    GROUP BY months.month
    ORDER BY months.month;
    ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':year', $year, PDO::PARAM_INT);
        $stmt->execute();

        $monthlyOrders = [];
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            $monthlyOrders[$monthNames[$row['month'] - 1]] = $row['totalOrders'];
        }

        foreach ($monthNames as $monthName) {
            if (!isset($monthlyOrders[$monthName])) {
                $monthlyOrders[$monthName] = 0;
            }
        }

        return $monthlyOrders;
    }

    public function getMonthlyCompletedProfit1($year)
    {
        $monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        $query = "
    SELECT 
        MONTH(CreatedAt) AS month,
        SUM(Total) AS completedProfit
    FROM `order`
    WHERE YEAR(CreatedAt) = :year
    AND Status = 'Completed'
    GROUP BY MONTH(CreatedAt)
    ORDER BY month
    ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':year', $year, PDO::PARAM_INT);
        $stmt->execute();

        $monthlyProfit = array_fill_keys($monthNames, 0);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            $monthIndex = $row['month'] - 1;
            $monthlyProfit[$monthNames[$monthIndex]] = $row['completedProfit'];
        }

        return $monthlyProfit;
    }

    public function getOrderHistory($customerId)
    {
        $query = "
    SELECT 
        o.OrderId, 
        o.CustomerId,
        c.Username,  -- Menambahkan informasi Username dari tabel customer
        o.Total,
        o.PaymentMethod,
        o.Status,
        o.CreatedAt,
        d.Quantity,
        d.Subtotal,
        m.MenuName,
        m.Description,
        m.ImageUrl,
        m.Price
    FROM `order` o
    JOIN `orderdetail` d ON o.OrderId = d.OrderId
    JOIN `menu` m ON d.MenuId = m.MenuId
    JOIN `customer` c ON o.CustomerId = c.CustomerId  -- Menghubungkan tabel customer
    WHERE o.CustomerId = :customerId
    ORDER BY o.CreatedAt DESC
    ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':customerId', $customerId, PDO::PARAM_INT);
        $stmt->execute();

        $orderHistory = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $groupedOrderHistory = [];
        foreach ($orderHistory as $order) {
            $orderId = $order['OrderId'];
            if (!isset($groupedOrderHistory[$orderId])) {
                $groupedOrderHistory[$orderId] = [
                    'OrderId' => $order['OrderId'],
                    'CustomerId' => $order['CustomerId'],
                    'Username' => $order['Username'],
                    'Total' => $order['Total'],
                    'PaymentMethod' => $order['PaymentMethod'],
                    'Status' => $order['Status'],
                    'CreatedAt' => $order['CreatedAt'],
                    'items' => []
                ];
            }
            $groupedOrderHistory[$orderId]['items'][] = [
                'MenuName' => $order['MenuName'],
                'Description' => $order['Description'],
                'ImageUrl' => $order['ImageUrl'],
                'Price' => $order['Price'],
                'Quantity' => $order['Quantity'],
                'Subtotal' => $order['Subtotal']
            ];
        }

        return array_values($groupedOrderHistory);
    }

    public function getOrders($startDate = null, $endDate = null)
    {
        // Query untuk mengambil data pesanan dan detail pesanan
        if ($startDate && $endDate) {
            $query = "
            SELECT 
                o.OrderId,
                c.Username AS CustomerUsername,  -- Mengganti CustomerId dengan Username
                o.Total,
                o.Paid,
                o.Change,
                o.PaymentMethod,
                o.Status,
                o.CreatedAt,
                od.OrderDetailId,
                m.MenuName,  -- Mengganti MenuId dengan MenuName
                od.Quantity,
                od.Price,
                od.Subtotal
            FROM 
                `order` o
            LEFT JOIN 
                `orderdetail` od ON o.OrderId = od.OrderId
            LEFT JOIN 
                `customer` c ON o.CustomerId = c.CustomerId  -- Join dengan tabel customers
            LEFT JOIN 
                `menu` m ON od.MenuId = m.MenuId  -- Join dengan tabel menus
            WHERE
                o.CreatedAt BETWEEN :startDate AND :endDate
            ORDER BY
                o.CreatedAt ASC
        ";

            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':startDate' => $startDate,
                ':endDate' => $endDate
            ]);
        } else {
            $query = "
            SELECT 
                o.OrderId,
                c.Username AS CustomerUsername,  -- Mengganti CustomerId dengan Username
                o.Total,
                o.Paid,
                o.Change,
                o.PaymentMethod,
                o.Status,
                o.CreatedAt,
                od.OrderDetailId,
                m.MenuName,  -- Mengganti MenuId dengan MenuName
                od.Quantity,
                od.Price,
                od.Subtotal
            FROM 
                `order` o
            LEFT JOIN 
                `orderdetail` od ON o.OrderId = od.OrderId
            LEFT JOIN 
                `customer` c ON o.CustomerId = c.CustomerId  -- Join dengan tabel customers
            LEFT JOIN 
                `menu` m ON od.MenuId = m.MenuId  -- Join dengan tabel menus
            ORDER BY
                o.CreatedAt ASC
        ";

            $stmt = $this->db->prepare($query);
            $stmt->execute();
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getWeeklyOrderCount()
    {
        // Query SQL untuk mendapatkan jumlah pesanan per hari dalam minggu
        $query = "
    SELECT 
        days.dayOfWeek,
        COALESCE(COUNT(o.OrderId), 0) AS totalOrders
    FROM 
        (SELECT 1 AS dayOfWeek UNION ALL 
         SELECT 2 UNION ALL 
         SELECT 3 UNION ALL 
         SELECT 4 UNION ALL 
         SELECT 5 UNION ALL 
         SELECT 6 UNION ALL 
         SELECT 7) AS days
    LEFT JOIN `order` o 
        ON DAYOFWEEK(o.CreatedAt) = days.dayOfWeek
        AND YEARWEEK(o.CreatedAt, 1) = YEARWEEK(CURDATE(), 1)
    GROUP BY days.dayOfWeek
    ORDER BY days.dayOfWeek;
    ";

        // Persiapkan dan jalankan query
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        // Ambil hasil dan kembalikan dalam bentuk array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHourlyOrderCount()
    {
        // Query SQL untuk mendapatkan jumlah pesanan per jam
        $query = "
        SELECT 
            hours.hourOfDay, 
            COALESCE(COUNT(o.OrderId), 0) AS totalOrders
        FROM (
            -- Jam 18 hingga 23 hari ini
            SELECT 18 AS hourOfDay UNION ALL
            SELECT 19 UNION ALL
            SELECT 20 UNION ALL
            SELECT 21 UNION ALL
            SELECT 22 UNION ALL
            SELECT 23 UNION ALL
            -- Jam 00 hingga 2 besok
            SELECT 0 UNION ALL
            SELECT 1 UNION ALL
            SELECT 2
        ) AS hours
        LEFT JOIN `order` o 
            ON (
                HOUR(o.CreatedAt) = hours.hourOfDay AND (
                    -- Kondisi untuk jam 18-23 pada hari ini
                    (HOUR(o.CreatedAt) >= 18 AND DATE(o.CreatedAt) = CURDATE()) OR
                    -- Kondisi untuk jam 00-02 pada hari berikutnya
                    (HOUR(o.CreatedAt) BETWEEN 0 AND 2 AND DATE(o.CreatedAt) = DATE_ADD(CURDATE(), INTERVAL 1 DAY))
                )
            )
        GROUP BY hours.hourOfDay
        ORDER BY hours.hourOfDay;
    ";

        // Persiapkan dan jalankan query
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        // Ambil hasil dan kembalikan dalam bentuk array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMonthlyOrderCountByWeek($month, $year)
    {
        // Query SQL untuk mendapatkan jumlah pesanan per minggu dalam bulan dan tahun tertentu
        $query = "
            SELECT 
                weeks.weekInMonth,
                COALESCE(COUNT(o.OrderId), 0) AS totalOrders
            FROM (
                SELECT 1 AS weekInMonth UNION ALL
                SELECT 2 UNION ALL
                SELECT 3 UNION ALL
                SELECT 4 UNION ALL
                SELECT 5
            ) AS weeks
            LEFT JOIN `order` o 
                ON FLOOR((DAY(o.CreatedAt) - 1) / 7) + 1 = weeks.weekInMonth
                AND MONTH(o.CreatedAt) = :month
                AND YEAR(o.CreatedAt) = :year
            GROUP BY weeks.weekInMonth
            ORDER BY weeks.weekInMonth;
        ";

        // Persiapkan dan jalankan query
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':month', $month, PDO::PARAM_INT);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->execute();

        // Ambil hasil dan kembalikan dalam bentuk array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function donutChartStatusOrder()
    {
        $query = "
    SELECT 
        o.Status,
        COUNT(o.OrderId) AS totalOrders
    FROM `order` o
    WHERE o.Status IN ('Pending', 'Processing', 'Completed', 'Cancelled')
    GROUP BY o.Status;
    ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $status = [];
        $statusData = [];

        foreach ($result as $row) {
            $status[] = $row['Status'];  // Ini akan menjadi array di PHP
            $statusData[] = $row['totalOrders'];
        }

        return [
            'status' => $status,  // Kirim status sebagai array
            'statusData' => $statusData  // Kirim statusData sebagai array
        ];
    }
}
