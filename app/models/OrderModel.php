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
            SELECT SUM(TotalAmount) as completedProfit
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

        return $result['completedProfit'] ?? 0; // Jika tidak ada hasil, kembalikan 0
    }

    public function getCurrentMonthCompletedProfit()
    {
        $currentMonth = date('n'); // Bulan saat ini (1–12)
        $currentYear = date('Y'); // Tahun saat ini

        return $this->getMonthlyCompletedProfit($currentMonth, $currentYear);
    }
}
