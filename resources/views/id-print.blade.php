<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Print ID - {{ $form->fname }} {{ $form->lname }}</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        html, body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background: #fff;
            font-family: Arial, Helvetica, sans-serif;
        }

        .print-container {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 25mm; /* space between front and back */
            padding: 25mm; /* keeps both cards inside safe zone */
            box-sizing: border-box;
        }

        .id-card {
            width: 85.6mm;
            height: 54mm;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media print {
            html, body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            /* Remove browser header/footer */
            @page :footer { display: none; }
            @page :header { display: none; }
        }

    </style>
</head>
<body onload="setTimeout(()=>window.print(), 250);">
    <div class="print-container">
        <div class="id-card">
            @include('components.id-card-side', ['form' => $form, 'side' => 'front'])
        </div>
        <div class="id-card">
            @include('components.id-card-side', ['form' => $form, 'side' => 'back'])
        </div>
    </div>
</body>
</html>
