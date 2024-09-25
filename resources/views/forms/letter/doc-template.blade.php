<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Letter</title>


    <style>
        body {
    font-family: 'Times New Roman', Times, serif, sans-serif;
    margin: 0;
    padding: 20px;
    background-color: #f0f0f0;
}

.letter-container {
    max-width: 800px;
    margin: 0 auto;
    background-color: white;
    padding: 40px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    position: relative;
}

.header {
    text-align: center;
    color: #333;
}



.logo-container {
    text-align: center;
    margin-bottom: 20px;
}

.logo {
    max-width: 100px;
}

.letter-header {
    text-align: center;
    /* margin-bottom: 30px; */
}

.date {
    text-align: right;
    margin-top: 20px;
}

.letter-body {
    margin-bottom: 30px;
}

.indent {
    margin-left: 40px;
}

.content {
    text-align: justify;
    line-height: 1.6;
}

.signature {
    text-align: right;
    margin-top: 40px;
}

.sign-image {
    max-width: 150px;
}

.qr-code {
    position: absolute;
    bottom: 40px;
    left: 40px;
}

.qr-image {
    max-width: 100px;
}

.footer {
    margin-top: 30px;
    /* border-top: 1px solid #ccc; */
    padding-top: 20px;
}

ol {
    padding-left: 20px;
}
    </style>
</head>
<body>
    <div class="letter-container">

        <!-- <div class="watermark">Specimen</div> -->
         <div class="row" style="display: flex; justify-content:space-around;">
            <div class="col-md-4">
             <div class="logo-container">
                 <img src="download.png" alt="Logo" class="logo">
             </div>
            </div>
            <div class="col-md-8">
                <div class="letter-header" style="line-height: 3px;">
                    <p>{{$letter->letter_no}}</p>
                    <p><strong>GOVERNMENT OF SINDH</strong></p>
                    <p><strong>ANTI-CORRUPTION ESTABLISHMENT</strong></p>
                    <p><strong>{{$letter->head_title}}</strong></p>
                    <p style="font-size: 12px;font-weight: bold;">{{$letter->fix_address}}</p>
                    <p style="font-size: 12px;font-weight: bold">Phone No: {{Auth::user()->contact}}, Fax: {{Auth::user()->tel}}</p>
                </div>
            </div>

         </div>
        <p class="date"><strong>Dated: the {{date('dS M, Y',strtotime($letter->date))}}</strong></p>
        <div class="letter-body">
            <div class="container-fluid">
                <p><strong>To,</strong></p>

            </div>
            <div class="container" style="margin-left: 70px;line-height: 4px; font-weight: bold;font-size: 15px;">
                @foreach($letter->designations as $toLetter)
                <p class="indent">{{$toLetter->designation}},</p>
                <p class="indent">{{$toLetter->department}},</p>
                <p class="indent" style="">{{$toLetter->address}}</p>
                @if (!empty($toLetter->contact))
                    <p class="indent" style="margin-bottom: 30px;">{{$toLetter->contact}}</p>
                @endif
                @endforeach
            </div>
            <div style="display: flex;">
                <p><strong>SUBJECT:</strong></p>
                    <p style="margin-left:30px ;"><strong>{{strtoupper($letter->subject)}}</p></strong>

            </div>

            <p class="content" style="line-height: 15px;font-size: 13px;">
                {!! $letter->draft_para !!}
            </p>

        </div>
        <div class="">
            <div class="row" style="display: flex; justify-content: space-around;align-items: center;">
                <div class="col-md-4">
                    <img src="qr-code.png" alt="Signature" class="" style="width: 100px;">

                </div>
                <div class="col-md-8" style="text-align: center;line-height: 4px;margin-top: 20px;">
                    @foreach ($letter->signingAuthorities as $Authority)
                    <p><strong>{{$Authority->designation}}</strong></p>
                    <p>For{{ $Authority->department}}</p>
                    <p>0301-2255945</p>
                    @endforeach
                </div>
            </div>

        </div>

        <div class="footer">
            <p>A copy is forwarded for similar compliance:-</p>
            <ol>
                @foreach($letter->forwardedCopies as $forward)
                <li>{{$forward->copy_forwarded}}</li>
              @endforeach
            </ol>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
