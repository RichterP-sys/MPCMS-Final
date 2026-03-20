<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $receipt->receipt_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica', 'Arial', sans-serif;
            color: #1f2937;
            line-height: 1.6;
            background: #f9fafb;
        }

        .print-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 0;
        }

        .receipt {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 3px solid #10b981;
        }

        .receipt-logo {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: bold;
            margin: 0 auto 15px;
        }

        .receipt-title {
            font-size: 28px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .receipt-subtitle {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .receipt-number {
            font-size: 18px;
            font-weight: bold;
            color: #059669;
            font-family: 'Courier New', monospace;
            margin-top: 15px;
            word-break: break-all;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 10px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .info-label {
            color: #6b7280;
            font-weight: 500;
            flex: 1;
        }

        .info-value {
            color: #1f2937;
            font-weight: 600;
            text-align: right;
            flex: 1;
        }

        .info-value.highlight {
            color: #059669;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .amount-box {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
            border: 2px solid #10b981;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }

        .amount-label {
            font-size: 11px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .amount-value {
            font-size: 32px;
            font-weight: 900;
            color: #059669;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 8px;
        }

        .status-badge.issued {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }

        .status-badge.pending {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fcd34d;
        }

        .footer {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid #e5e7eb;
            margin-top: 30px;
            color: #6b7280;
            font-size: 12px;
        }

        .footer p {
            margin: 5px 0;
        }

        .approval-stamp {
            text-align: center;
            margin: 20px 0;
        }

        .stamp-icon {
            width: 50px;
            height: 50px;
            margin: 0 auto 10px;
            background: #f0fdf4;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #059669;
            font-size: 28px;
        }

        .stamp-text {
            font-size: 14px;
            font-weight: 700;
            color: #059669;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .print-container {
                padding: 0;
            }
            .receipt {
                box-shadow: none;
                padding: 30px;
            }
            .print-btn {
                display: none;
            }
        }

        @media (max-width: 600px) {
            .receipt {
                padding: 20px;
            }
            .grid-2 {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            .receipt-title {
                font-size: 22px;
            }
            .amount-value {
                font-size: 28px;
            }
        }

        .print-btn {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
            width: 100%;
        }

        .print-btn:hover {
            background: #2563eb;
        }
    </style>
</head>
<body>
    <div class="print-container">
        <button class="print-btn" onclick="window.print()">🖨️ Print Receipt</button>

        <div class="receipt">
            <!-- Receipt Header -->
            <div class="receipt-header">
                <div class="receipt-logo">✓</div>
                <div class="receipt-title">PAYMENT RECEIPT</div>
                <div class="receipt-subtitle">Official Transaction Record</div>
                <div class="receipt-number">{{ $receipt->receipt_number }}</div>
            </div>

            <!-- Receipt and Member Info -->
            <div class="grid-2">
                <!-- Receipt Info -->
                <div class="section">
                    <div class="section-title">Receipt Information</div>
                    <div class="info-row">
                        <span class="info-label">Issued Date:</span>
                        <span class="info-value">{{ $receipt->receipt_issued_at->format('M d, Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Issued Time:</span>
                        <span class="info-value">{{ $receipt->receipt_issued_at->format('h:i A') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="info-value">
                            <span class="status-badge {{ $receipt->receipt_status }} ">
                                {{ ucfirst($receipt->receipt_status) }}
                            </span>
                        </span>
                    </div>
                </div>

                <!-- Member Info -->
                <div class="section">
                    <div class="section-title">Member Information</div>
                    <div class="info-row">
                        <span class="info-label">Name:</span>
                        <span class="info-value">{{ $receipt->member->first_name }} {{ $receipt->member->last_name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Member ID:</span>
                        <span class="info-value">{{ $receipt->member->member_id }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value" style="font-size: 11px;">{{ $receipt->member->email }}</span>
                    </div>
                </div>
            </div>

            <!-- Transaction Details -->
            <div class="section">
                <div class="section-title">Transaction Details</div>
                <div class="info-row">
                    <span class="info-label">Transaction Type:</span>
                    <span class="info-value">{{ ucfirst($receipt->record_type) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Reference ID:</span>
                    <span class="info-value">#{{ $receipt->record_id }}</span>
                </div>
            </div>

            <!-- Amount Box -->
            <div class="amount-box">
                <div class="amount-label">Payment Amount</div>
                <div class="amount-value">₱{{ number_format($receipt->amount, 2) }}</div>
            </div>

            <!-- Approval Stamp -->
            <div class="approval-stamp">
                <div class="stamp-icon">✓</div>
                <div class="stamp-text">Payment Received & Processed</div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p><strong>{{ config('app.name', 'MPCMS') }}</strong></p>
                <p>Cooperative Management System</p>
                <p style="margin-top: 15px; font-size: 11px;">Printed on {{ now()->format('F d, Y \a\t h:i A') }}</p>
                <p style="font-size: 10px; color: #9ca3af; margin-top: 10px;">This is an official receipt for transactions made with the cooperative. Please keep it for your records.</p>
            </div>
        </div>
    </div>
</body>
</html>
