<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Government of Sindh Letter</title>
    <style>
         /* * {
        border: 1px solid red !important;
    } */
    .no-break {
        page-break-inside: avoid;
    }
        .pdf-no-break {
        page-break-inside: avoid !important;
        -pdf-page-break-inside: avoid !important;
    }
        body {
            font-family: 'Times New Roman', Times;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            vertical-align: top;
            padding: 5px;
        }
        .center {
            text-align: center;
        }
        .right {
            text-align: right;
        }.indent{
            text-indent: 6em;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <td width="60">
                <img src="{{ storage_path('app/public/qr-codes/download.png') }}" alt="Government Logo" width="200" height="100">
            </td>
            @if ($letter->user->designation==strtolower('Chairman'))
            <td class="center" style="font-size:14px;line-height: 2px;">
                <h2 style="margin-left: 50px;">{{$letter->letter_no}}</h2>
                <h2 style="margin-left: 50px;">GOVERNMENT OF SINDH</h2>
                <h2 style="margin-left: 50px;">ANTI-CORRUPTION ESTABLISHMENT</h2>
                <h2 style="margin-left: 50px;">Chairman Office</h2>
                <p style="margin-left: 50px;">{{$letter->fix_address}}</p>
                <p style="margin-left: 50px;">Phone No: {{ $letter->user->contact}}, Fax: {{$letter->user->tel}}</p>
            </td>
            @elseif ($letter->user->designation == strtolower('Director'))
            <td class="center" style="font-size:14px;line-height: 2px;">
                <h2 style="margin-left: 50px;">{{$letter->letter_no}}</h2>
                <h2 style="margin-left: 50px;">GOVERNMENT OF SINDH</h2>
                <h2 style="margin-left: 50px;">ANTI-CORRUPTION ESTABLISHMENT</h2>
                <h2 style="margin-left: 50px;">HEAD QUATOR</h2>
                <p style="margin-left: 50px;">{{$letter->fix_address}}</p>
                <p style="margin-left: 50px;">Phone No: {{ $letter->user->contact}}, Fax: {{$letter->user->tel}}</p>
            </td>
            @elseif ($letter->user->designation ==  strtolower('Deputy Director'))
            <td class="center" style="font-size:14px;line-height: 2px;">
                <h2 style="margin-left: 50px;">{{$letter->letter_no}}</h2>
                <h2 style="margin-left: 50px;">GOVERNMENT OF SINDH</h2>
                <h2 style="margin-left: 50px;">ANTI-CORRUPTION ESTABLISHMENT</h2>
                <h2 style="margin-left: 50px;text-transform:capitalize;">{{$letter->head_title}}</h2>
                <p style="margin-left: 50px;">{{$letter->fix_address}}</p>
                <p style="margin-left: 50px;">Phone No: {{ $letter->user->contact}}, Fax: {{$letter->user->tel}}</p>
            </td>
            @elseif ($letter->user->designation == strtolower('Assistant Director') || strtolower('Circle Officer') || strtolower('Inspector') || strtolower('Sub inspector' ))

            <td class="center" style="font-size:14px;line-height: 2px;">
                <h2 style="margin-left: 50px;">{{$letter->letter_no}}</h2>
                <h2 style="margin-left: 50px;">GOVERNMENT OF SINDH</h2>
                <h2 style="margin-left: 50px;">ANTI-CORRUPTION ESTABLISHMENT</h2>
                <h2 style="margin-left: 50px;">Office Of The Circle Officer </h2>
                <p style="margin-left: 50px;">{{$letter->head_title}}</p>
                <p style="margin-left: 50px;">Phone No: {{ $letter->user->contact}}, Fax: {{$letter->user->tel}}</p>
            </td>
            @endif
        </tr>
    </table>

    <table>
        <tr>
            <td class="right">
                <p style="font-weight: 600;">Dated: the {{date('dS M, Y',strtotime($letter->date))}}</p>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="width: 100px !important;">
                <p style="font-weight: 500;">To,</p>
            </td>
            <td>
                <p style="font-weight: 700;margin-top: 60px;">
                    @foreach($letter->designations as $toLetter)
                    <p style="margin-top: 20px;margin-right:0px;font-weight: 700">
                        {{$toLetter->designation}},<br>
                        {{$toLetter->department}},<br>
                        {{$toLetter->address}},<br>
                        @if (!empty($toLetter->contact))
                        {{$toLetter->contact}}
                    @endif
                    </p>
                    @endforeach
                </p>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <!-- <td>
                <h3 style="text-decoration: underline;">SUBJECT: REQUEST FOR CURRENT STATUS OF PENDING FIRs/ENQUIRIES & CASES AGAINST REGULAR/CONTRACTOR/DAILY BASIS</h3>
            </td> -->
           <td style="width: 100px !important;">
            <p style="font-weight: 700;">Subject:</p>
            </td>
           <td>
            <p style="font-weight: 700;text-decoration: underline;">
                {{strtoupper($letter->subject)}}
             </p>
           </td>

        </tr>
    </table>

    {{-- <div style="">page-break-inside:avoid; page-break-before: avoid;--}}

                <div class="" style="margin: 0 auto; text-align: justify;text-indent:7em;" >
                    <p style="text-indent:7em;" >{!! $letter->draft_para !!}</p>
                </div>


    <table style="margin-top: 50px;">
        <tr>
            <td>
                <img src="{{ storage_path('app/public/' . $letter->qr_code) }}" alt="QR Code" width="90" height="90" style="margin-left: 20px;">

            </td>
            <td class="right" style="text-align: center;line-height: 2px;">
                @foreach ($letter->signingAuthorities as $Authority)
                <p style="margin-top: 20px;">
                    <p><strong>{{$Authority->name}}</strong></p>
                    <p>{{$Authority->designation}}</p>
                    <p>For {{ $Authority->department}}</p>
                    <p>0301-2255945</p>
                </p>
                @endforeach
            </td>
        </tr>
    </table>

    <table style="page-break-after: unset;">
        <tr>
            <td>
                <p><strong>A copy is forwarded for similar compliance:-</strong></p>
                <ol>
                    @foreach($letter->forwardedCopies as $forward)
                    <li>{{$forward->copy_forwarded}}</li>
                    @endforeach
                </ol>
            </td>
        </tr>
    </table>
</body>
</html>
