
<x-filament::page>
    @if($duplicateNames)
        <div>
            <h1>Duplication Check by Name</h1>
            <p>Most duplications were generated by the formSite data entry. Typos and different spellings are not caught there. </p>
            <p>This list is one way to catch duplication in first and last names with different email address. Email is a key that does not allow dupliccation.</p>
            <p>Use this list to resolve dups by editing the incorrect record, moving the staff supervised if needed</p>
            <p>TODO list supervisees to transfer</p>
        </div>
        
        <table>
            <thead>
                <th>Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Effective Date</th>
                <th>Supervisor</th>
                <th>Actions</th>

            </thead>
            <tbody>
                @foreach ( $duplicateNames as $dup )
                    <tr> 
                        <td>{{ $dup['user_id'] }} </td>
                        <td>{{ $dup['user_name'] }} </td>
                        <td>{{ $dup['email'] }} </td>
                        <td>{{ $dup['effective'] }} </td>
                        <td>{{ $dup['super'] }} </td>
                        <td> <a href="users/{{ $dup['user_id'] }}/edit" target="_blank">Edit</a></td>
                        {{-- <td> <a href="users/{{ $dup['user_id'] }}/edit?activeRelationManager=1" target="_blank">Supervising</a></td> --}}
                        {{-- http://localhost:8000/admin/users/3/edit?activeRelationManager=1 --}}
                        
                    </tr>
                @endforeach
            </tbody>
        <table>
    @endif
</x-filament::page>
