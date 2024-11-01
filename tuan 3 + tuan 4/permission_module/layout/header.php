<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng</title>
    <link rel="stylesheet" href="../permission/permission.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .actions {
            display: flex;
            gap: 5px;
        }

        .pagination {
            text-align: right;
            margin-top: 10px;
        }

        .add-new-button {
            margin-top: 10px;
        }

        .dashboard {
            display: flex;
        }

        .sidebar {
            width: 200px;
            background-color: #f8f8f8;
            padding: 10px;
        }

        .content {
            flex-grow: 1;
            padding: 10px;
        }

        .overview {
            margin-top: 20px;
        }
    </style>
</head>
<body>
