<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>არქივის ყოველთვიური რეპორტი</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f6f7f9; padding:20px;">

<div style="max-width:800px; margin:auto; background:#ffffff; padding:20px; border-radius:8px;">

    <h2 style="margin-top:0;">
        📊 დოკუმენტის არქივის ყოველთვიური რეპორტი
    </h2>
    @php
        $months = [
            1 => 'იანვარი',
            2 => 'თებერვალი',
            3 => 'მარტი',
            4 => 'აპრილი',
            5 => 'მაისი',
            6 => 'ივნისი',
            7 => 'ივლისი',
            8 => 'აგვისტო',
            9 => 'სექტემბერი',
            10 => 'ოქტომბერი',
            11 => 'ნოემბერი',
            12 => 'დეკემბერი',
        ];
    @endphp

    <p>
        პერიოდი:
        <strong>{{ $months[$period->month] }}, {{ $period->year }}</strong>
    </p>

    <table width="100%" cellpadding="8" cellspacing="0"
           style="border-collapse: collapse; font-size:14px; margin-top:15px;">

        <thead>
        <tr style="background:#f1f3f5;">
            <th align="left" style="border:1px solid #ddd;">კომპანია</th>
            <th align="left" style="border:1px solid #ddd;">ხელშეკრულების ტიპი</th>
            <th align="center" style="border:1px solid #ddd;">დაარქივებული დოკუმენტები</th>
        </tr>
        </thead>

        <tbody>
        @forelse($report as $row)
            <tr>
                <td style="border:1px solid #ddd;">
                    {{ $row['company'] }}
                </td>
                <td style="border:1px solid #ddd;">
                    {{ $row['contract_type'] }}
                </td>
                <td align="center" style="border:1px solid #ddd; font-weight:bold;">
                    {{ $row['total'] }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" align="center"
                    style="border:1px solid #ddd; padding:15px;">
                    No documents were uploaded in this period.
                </td>
            </tr>
        @endforelse
        </tbody>

    </table>

    <p style="margin-top:25px; font-size:13px; color:#666;">
        This report was generated automatically by the Document Archive System.
    </p>

</div>

</body>
</html>
