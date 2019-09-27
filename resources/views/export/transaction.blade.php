<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Wallet</th>
            <th>Category</th>
            <th>Type</th>
            <th>Details</th>
            <th>Amount</th>
            <th>Benefit wallet</th>
            <th>Created at</th>
            <th>Updated at</th>
            <th>Deleted</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transaction as $item)
        <tr>
            <td>{{ $item['id'] }}</td>
            <td>{{ $item['wallet'] }}</td>
            <td>{{ $item['cat'] }}</td>
            <td>{{ $item['type'] }}</td>
            <td>{{ $item['details'] }}</td>
            <td>{{ $item['amount'] }}</td>
            <td>{{ $item['benefit_wallet'] }}</td>
            <td>{{ $item['created_at'] }}</td>
            <td>{{ $item['updated_at'] }}</td>
            <td>{{ $item['delete_flag'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
