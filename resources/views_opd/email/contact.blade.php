<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <style type="text/css">
    </style>
</head>
<body>
    <table>
        <tr>
            <th>Name</th>
            <td>{{ $data['name'] }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $data['email'] }}</td>
        </tr>
        <tr>
            <th>Mobile</th>
            <td>{{ $data['phone'] }}</td>
        </tr>
        <tr>
            <td colspan="2">{{ $data['text'] }}</td>
        </tr>
        <tr>
            <td></td>
            <td>{{ $data['address'] }}</td>
        </tr>
    </table>
</body>
</html>
