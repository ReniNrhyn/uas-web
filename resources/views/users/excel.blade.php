<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Email</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $vall)
        <tr>
            <td>{{ $vall->name }}</td>
            <td>{{ $vall->email }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
