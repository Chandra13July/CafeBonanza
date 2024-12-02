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
        // Prepare the base query
        $query = "
        SELECT 
            o.OrderId,
            c.Username AS Customer,
            o.Total,
            o.PaymentMethod,
            o.Status,
            o.CreatedAt
        FROM `order` o
        JOIN `customer` c ON o.CustomerId = c.CustomerId
        WHERE 1=1
    ";

        // Add date filter condition if startDate and endDate are provided
        if ($startDate && $endDate) {
            $query .= " AND o.CreatedAt BETWEEN :startDate AND :endDate";
        }

        // Order the results by creation date
        $query .= " ORDER BY o.CreatedAt DESC";

        // Prepare the SQL statement
        $stmt = $this->db->prepare($query);

        // Bind parameters if date range is provided
        if ($startDate && $endDate) {
            $stmt->bindValue(':startDate', $startDate, PDO::PARAM_STR);
            $stmt->bindValue(':endDate', $endDate, PDO::PARAM_STR);
        }

        // Execute the query
        $stmt->execute();

        // Return the results as an associative array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderReceipt($orderId)
    {
        // Query untuk mendapatkan data pesanan
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

        // Query untuk mendapatkan detail item dalam pesanan
        $queryOrderDetails = "
    SELECT 
        d.MenuId,
        m.MenuName,
        d.Quantity,
        d.Price,
        d.Subtotal
    FROM `orderdetails` d
    JOIN `menu` m ON d.MenuId = m.MenuId
    WHERE d.OrderId = :orderId
    ";

        // Ambil data pesanan
        $stmtOrder = $this->db->prepare($queryOrder);
        $stmtOrder->bindValue(':orderId', $orderId, PDO::PARAM_INT);
        $stmtOrder->execute();
        $order = $stmtOrder->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            throw new Exception("Order dengan ID $orderId tidak ditemukan.");
        }

        // Ambil data detail pesanan
        $stmtOrderDetails = $this->db->prepare($queryOrderDetails);
        $stmtOrderDetails->bindValue(':orderId', $orderId, PDO::PARAM_INT);
        $stmtOrderDetails->execute();
        $orderDetails = $stmtOrderDetails->fetchAll(PDO::FETCH_ASSOC);

        // Format data untuk struk
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
}
