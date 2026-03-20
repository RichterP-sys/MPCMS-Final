<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $repayment->receipt_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            background: white;
            padding: 20px;
        }

        .receipt {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border: 2px solid #10b981;
            border-radius: 8px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px dashed #10b981;
            padding-bottom: 20px;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #10b981;
            margin-bottom: 10px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 12px;
            color: #666;
        }

        .content {
            margin: 30px 0;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 12px;
            letter-spacing: 1px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
        }

        .label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .value {
            font-weight: 600;
            color: #333;
            text-align: right;
        }

        .amount {
            color: #10b981;
            font-size: 14px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin: 20px 0;
        }

        .grid-item {
            border-left: 2px solid #e5e7eb;
            padding-left: 20px;
        }

        .summary {
            background: linear-gradient(135deg, #d1fae5, #ccfbf1);
            padding: 20px;
            border-radius: 6px;
            margin: 25px 0;
            border: 1px solid #a7f3d0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
        }

        .summary-row:last-child {
            margin-bottom: 0;
            padding-top: 10px;
            border-top: 1px solid #a7f3d0;
            font-weight: bold;
            font-size: 16px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 3px dashed #10b981;
            font-size: 11px;
            color: #666;
        }

        .footer-icon {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .approved-text {
            color: #10b981;
            font-weight: bold;
            font-size: 14px;
            margin: 10px 0;
        }

        @media print {
            body {
                padding: 0;
            }
            .receipt {
                border: none;
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <div class="logo">✓</div>
            <div class="title">PAYMENT RECEIPT</div>
            <div class="subtitle">Loan Repayment Documentation</div>
        </div>

        <!-- Main Content -->
        <div class="content">
            <!-- Receipt & Member Info -->
            <div class="grid">
                <div>
                    <div class="section-title">Receipt Information</div>
                    <div class="row">
                        <span class="label">Receipt Number</span>
                        <span class="value" style="font-family: monospace;">{{ $repayment->receipt_number }}</span>
                    </div>
                    <div class="row">
                        <span class="label">Issued Date</span>
                        <span class="value">{{ $repayment->receipt_issued_at?->format('F d, Y') }}</span>
                    </div>
                    <div class="row">
                        <span class="label">Issued Time</span>
                        <span class="value">{{ $repayment->receipt_issued_at?->format('h:i A') }}</span>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="section-title">Member Information</div>
                    <div class="row">
                        <span class="label">Member Name</span>
                        <span class="value">{{ $member->first_name }} {{ $member->last_name }}</span>
                    </div>
                    <div class="row">
                        <span class="label">Member ID</span>
                        <span class="value">{{ $member->member_id }}</span>
                    </div>
                    <div class="row">
                        <span class="label">Email</span>
                        <span class="value" style="font-size: 11px;">{{ $member->email }}</span>
                    </div>
                </div>
            </div>

            <!-- Repayment Details -->
            <div class="section">
                <div class="section-title">Repayment Details</div>
                <div class="grid">
                    <div>
                        <div class="row">
                            <span class="label">Loan Number</span>
                            <span class="value">#{{ $loan->id }}</span>
                        </div>
                        <div class="row">
                            <span class="label">Original Loan Amount</span>
                            <span class="value">₱{{ number_format($loan->amount, 2) }}</span>
                        </div>
                        <div class="row">
                            <span class="label">Loan Purpose</span>
                            <span class="value" style="font-size: 12px;">{{ $loan->loan_purpose ?? 'General Loan' }}</span>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="row">
                            <span class="label">Payment Amount</span>
                            <span class="value amount">₱{{ number_format($repayment->amount, 2) }}</span>
                        </div>
                        <div class="row">
                            <span class="label">Payment Date</span>
                            <span class="value">{{ $repayment->payment_date->format('F d, Y') }}</span>
                        </div>
                        <div class="row">
                            <span class="label">Payment Method</span>
                            <span class="value">{{ ucfirst($repayment->payment_method) }}</span>
                        </div>
                    </div>
                </div>
                @if($repayment->reference_number)
                    <div class="row" style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e5e7eb;">
                        <span class="label">Reference Number</span>
                        <span class="value" style="font-family: monospace;">{{ $repayment->reference_number }}</span>
                    </div>
                @endif
            </div>

            <!-- Summary -->
            <div class="summary">
                <div class="summary-row">
                    <span>Repayment Amount:</span>
                    <span>₱{{ number_format($repayment->amount, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span>Total Paid:</span>
                    <span>₱{{ number_format($repayment->amount, 2) }}</span>
                </div>
                @if($loan->remaining_balance !== null)
                    <div class="summary-row" style="border-top: none; padding-top: 8px; font-weight: normal; font-size: 12px;">
                        <span>Remaining Balance:</span>
                        <span>₱{{ number_format($loan->remaining_balance, 2) }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-icon">✓</div>
            <div class="approved-text">Payment Received & Processed</div>
            <p>This is an official receipt for the loan repayment transaction.</p>
            <p style="margin-top: 15px;">{{ config('app.name', 'MPCMS') }} | Cooperative Management System</p>
            <p style="margin-top: 10px; font-size: 10px;">Receipt generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
        </div>
    </div>
</body>
</html>
