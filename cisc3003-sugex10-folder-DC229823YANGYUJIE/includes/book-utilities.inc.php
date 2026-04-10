<?php
function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function readCustomers($filename) {
    $customers = [];

    if (!file_exists($filename)) {
        return $customers;
    }

    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return $customers;
    }

    foreach ($lines as $line) {
        $parts = array_map('trim', explode(';', $line));
        if (count($parts) < 12) {
            continue;
        }

        $customers[] = [
            'id' => $parts[0],
            'first_name' => $parts[1],
            'last_name' => $parts[2],
            'email' => $parts[3],
            'university' => $parts[4],
            'address' => $parts[5],
            'city' => $parts[6],
            'state' => $parts[7],
            'country' => $parts[8],
            'zip' => $parts[9],
            'phone' => $parts[10],
            'sales' => $parts[11]
        ];
    }

    return $customers;
}

function findCustomerById(array $customers, string $customerId) {
    foreach ($customers as $customer) {
        if ($customer['id'] === $customerId) {
            return $customer;
        }
    }
    return null;
}

function readOrders($customer, $filename) {
    $orders = [];
    $customerId = $customer['id'] ?? '';

    if ($customerId === '' || !file_exists($filename)) {
        return $orders;
    }

    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return $orders;
    }

    foreach ($lines as $line) {
        $parts = array_map('trim', explode(',', $line));
        if (count($parts) < 5) {
            continue;
        }

        if (trim($parts[1]) === $customerId) {
            $orders[] = [
                'order_id' => trim($parts[0]),
                'customer_id' => trim($parts[1]),
                'isbn' => trim($parts[2]),
                'title' => trim($parts[3]),
                'category' => trim($parts[4])
            ];
        }
    }

    return $orders;
}

function findBookCover($isbn) {
    $isbn = trim($isbn);
    if ($isbn === '') {
        return '';
    }

    $possible = [
        'images/tinysquare/' . $isbn . '.jpg',
        'images/tinysquare/' . $isbn . '.png',
        'images/tinysquare/' . $isbn . '.jpeg',
        'images/tinysquare/' . $isbn . '.webp'
    ];

    foreach ($possible as $path) {
        if (file_exists($path)) {
            return $path;
        }
    }

    return '';
}