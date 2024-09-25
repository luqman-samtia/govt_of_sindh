<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Super Admin Subscriber Excel</title>

</head>

<body>
    @php
        $styleCss = 'style';
    @endphp
    <table>
        <thead>
            <tr>
                <th {{ $styleCss }}="width: 500%"><b>Email Address</b></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subscribers as $subscriber)
                <tr>
                    <td>{{ $subscriber->email }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
