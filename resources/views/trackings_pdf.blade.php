<!DOCTYPE html>
<html>
<head>
    <title>Tracking Logs</title>
</head>
<body>
<h1>Tracking Logs</h1>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Item Name</th>
        <th>School Name</th>
        <th>Priority</th>
        <th>Action</th>
        <th>Quantity</th>
        <th>Reference Number</th>
        <th>Additional Info</th>
        <th>Start Point</th>
        <th>Current Point</th>
        <th>Date Moved</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($trackings as $tracking)
        <tr>
            <td>{{ $tracking->id }}</td>
            <td>{{ $tracking->item->item_name }}</td>
            <td>{{ $tracking->school->name }}</td>
            <td>{{ $tracking->priority }}</td>
            <td>{{ $tracking->action }}</td>
            <td>{{ $tracking->quantity }}</td>
            <td>{{ $tracking->reference_number }}</td>
            <td>{{ $tracking->additional_info }}</td>
            <td>{{ $tracking->start_point }}</td>
            <td>{{ $tracking->current_point }}</td>
            <td>{{ $tracking->date_moved }}</td>
            <td>{{ $tracking->status }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
