<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Activity Log Report</h1>
        <p>Date: {{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>User id</th>
                <th>Description</th>
                <th>IP address</th>
                <th>Name</th>
                <th>Action Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $log)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $log->user_id }}</td>
                    <td>{{ $log->description }}</td>
                    <td>{{ $log->ip_address }}</td>
                    <td>{{ $log->throttle_key }}</td>
                    <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d-m-Y H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
