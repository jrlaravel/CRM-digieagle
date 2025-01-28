<!DOCTYPE html>
<html>
<head>
    <title>Expiry Notification Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #000000;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: #ff9a02da;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 1.7em;
            font-weight: bold;
        }

        .content {
            padding: 20px;
        }

        .client-section {
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
        }

        .client-header {
            background-color: #ffffff;
            color: rgb(0, 0, 0);
            padding: 10px;
            font-size: 1.5em;
            font-weight: bold;
        }

        .details {
            padding: 10px;
            background-color: #f8f9fa;
        }

        .details h4 {
            margin: 10px 0;
            color: #343a40;
        }

        .details p {
            margin: 5px 0;
            line-height: 1.5;
            font-size: 1.2em;
            color: #000000;
        }

        .details a {
            color: #007bff;
            text-decoration: none;
        }

        .details a:hover {
            text-decoration: underline;
        }

        .note {
            margin-top: 20px;
            font-style: italic;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Upcoming Expiry Notification Report</div>
        <div class="content">
            <p>The following domains and hostings are about to expire in 5 days:</p>

            @foreach ($expiringItems as $item)
            <div class="client-section">
                <div class="client-header">Client: {{ $item->client_name }}</div>
                <div class="details">
                    <h4>Domain Details:</h4>
                    @if ($item->domain_expire_date)
                        <p><strong>Domain Link:</strong> {{ $item->domain_name }}</p>
                        <p><strong>Purchased From:</strong> {{ $item->domain_purchase_from }}</p>
                        <p><strong>Amount:</strong> ${{ $item->domain_amount }}</p>
                        <p><strong>Purchase Date:</strong> {{ \Carbon\Carbon::parse($item->domain_purchase_date)->format('d-m-Y') }}</p>
                        <p><strong>Expiry Date:</strong> {{ \Carbon\Carbon::parse($item->domain_expire_date)->format('d-m-Y') }}</p>
                        <p><strong>Email:</strong> {{ $item->domain_email }}</p>
                    @else
                        <p>No domain details available.</p>
                    @endif

                    <h4>Hosting Details:</h4>
                    @if ($item->hosting_expire_date)
                    <p><strong>Hosting Link:</strong> <a href="{{ $item->hosting_link }}">{{ $item->hosting_link }}</a></p>
                        <p><strong>Purchased From:</strong> {{ $item->hosting_purchase_from }}</p>
                        <p><strong>Amount:</strong> ${{ $item->hosting_amount }}</p>
                        <p><strong>Purchase Date:</strong> {{ \Carbon\Carbon::parse($item->hosting_purchase_date)->format('d-m-Y') }}</p>
                        <p><strong>Expiry Date:</strong> {{ \Carbon\Carbon::parse($item->hosting_expire_date)->format('d-m-Y') }}</p>
                        <p><strong>Email:</strong> {{ $item->hosting_email }}</p>
                    @else
                        <p>No hosting details available.</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</body>
</html>
