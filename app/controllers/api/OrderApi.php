<?php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../core/Database.php';

class OrderApi
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllOrders()
    {
        $this->db->query("SELECT * FROM `order`");
        $orders = $this->db->resultSet(); 

        $data = ["data" => []]; 

        foreach ($orders as $order) {
            $data_order = [
                "OrderId" => $order["OrderId"],
                "CustomerId" => $order["CustomerId"],
                "Total" => isset($order["Total"]) ? $order["Total"] : 0,
                "Paid" => isset($order["Paid"]) ? $order["Paid"] : 0,
                "Change" => isset($order["Change"]) ? $order["Change"] : 0,
                "PaymentMethod" => $order["PaymentMethod"] ?? 'N/A',
                "Status" => $order["Status"] ?? 'Unknown',
                "CreatedAt" => $order["CreatedAt"] ?? 'Unknown'
            ];

            array_push($data['data'], $data_order);
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }
}

try {
    $orderApi = new OrderApi();
    $method = $_SERVER['REQUEST_METHOD'];

    header('Content-Type: application/json');

    switch ($method) {
        case 'GET':
            $orderApi->getAllOrders();
            break;
        default:
            http_response_code(405);
            echo json_encode(["message" => "Method not allowed"]);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["message" => "Server error: " . $e->getMessage()]);
}
