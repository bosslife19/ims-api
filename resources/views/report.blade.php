<!DOCTYPE html>
<html>
<head>
    <title>Latest Inventory report</title>
</head>
<body>
<h1>Inventory Report</h1>
<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Category</th>
        <th>Location</th>
        <th>Stock Level</th>
        <th>Date Added</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($items as $item)
        <tr>
            <td>{{ $item->name }}</td>
            <td>{{ $item->category }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ $item->created_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>