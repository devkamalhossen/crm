<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>Invoice - {{ $invoice->invoice_number }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #333;
            margin: 0;
            padding: 30px;
        }

        .header {
            width: 100%;
            margin-bottom: 30px;
        }

        .company {
            float: left;
        }

        .invoice-info {
            float: right;
            text-align: right;
        }

        .clearfix {
            clear: both;
        }

        h1 {
            margin: 0 0 5px;
            font-size: 28px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background: #f5f5f5;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            width: 40%;
            margin-left: auto;
            margin-top: 20px;
        }

        .summary td {
            border: none;
            padding: 6px;
        }

        .total {
            font-size: 16px;
            font-weight: bold;
        }

        .footer {
            margin-top: 50px;
            font-size: 11px;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="header">

        <div class="company">
            <h1>Intellec IT</h1>

            <div>
                House 2/1, Road 2, Block C, Mirpur, Bangladesh
            </div>

            <div>
                Phone: +8801886-404458
            </div>

            <div>
                Email: intellecitltd@gmail.com
            </div>
        </div>

        <div class="invoice-info">

            <h1>INVOICE</h1>

            <strong>
                {{ $invoice->invoice_number }}
            </strong>

            <br>

            Invoice Date:
            {{ $invoice->invoice_date?->format('d M Y') }}

            <br>

            Due Date:
            {{ $invoice->due_date?->format('d M Y') }}

        </div>

        <div class="clearfix"></div>

    </div>


    {{-- Client Information --}}

    <div style="margin-bottom: 25px;">

        <strong>Bill To:</strong>

        <br>

        {{ $invoice->client?->name }}

        <br>

        {{ $invoice->client?->company_name }}

        <br>

        {{ $invoice->client?->phone }}

        <br>

        {{ $invoice->client?->email }}

    </div>


    {{-- Invoice Items --}}

    <table>

        <thead>

            <tr>
                <th width="45%">Item</th>
                <th width="15%">Qty</th>
                <th width="20%">Unit Price</th>
                <th width="20%">Amount</th>
            </tr>

        </thead>

        <tbody>

            @foreach ($invoice->items as $item)

                <tr>

                    <td>
                        <strong>
                            {{ $item->item_name }}
                        </strong>

                        @if ($item->description)
                            <br>
                            <small>
                                {{ $item->description }}
                            </small>
                        @endif
                    </td>

                    <td>
                        {{ $item->quantity }}
                    </td>

                    <td class="text-right">
                        ৳{{ number_format($item->unit_price, 2) }}
                    </td>

                    <td class="text-right">
                        ৳{{ number_format($item->amount, 2) }}
                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>


    {{-- Summary --}}

    <table class="summary">

        <tr>
            <td>Subtotal</td>

            <td class="text-right">
                ৳{{ number_format($invoice->subtotal, 2) }}
            </td>
        </tr>

        <tr>
            <td>
                Discount

                @if ($invoice->discount_type === 'percentage')
                    ({{ $invoice->discount_value }}%)
                @endif
            </td>

            <td class="text-right">
                ৳{{ number_format($invoice->discount_amount, 2) }}
            </td>
        </tr>

        <tr>
            <td>Tax</td>

            <td class="text-right">
                ৳{{ number_format($invoice->tax, 2) }}
            </td>
        </tr>

        <tr class="total">
            <td>Total</td>

            <td class="text-right">
                ৳{{ number_format($invoice->total_amount, 2) }}
            </td>
        </tr>

        <tr>
            <td>Paid</td>

            <td class="text-right">
                ৳{{ number_format($invoice->paid_amount, 2) }}
            </td>
        </tr>

        <tr class="total">
            <td>Due</td>

            <td class="text-right">
                ৳{{ number_format($invoice->due_amount, 2) }}
            </td>
        </tr>

    </table>


    @if ($invoice->notes)

        <div style="margin-top: 30px;">

            <strong>Notes:</strong>

            <p>
                {{ $invoice->notes }}
            </p>

        </div>

    @endif


    <div class="footer">

        Thank you for your business.

    </div>

</body>
</html>