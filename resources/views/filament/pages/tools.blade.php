<x-filament::page>
    <div>
        {{ $this->newestAction }}
    </div>
    <p> {{ $this->checkName }}</p>
    <p> {{ $this->checkSuper }}</p>
    {{-- <p> {{ $this->checkUnkSuper }}</p> --}}
    <p> {{ $this->genWall }}</p>
    <p> {{ $this->genSched }}</p>

    @if($duplicateNames)
        <div>
            <h1>Duplication Check by Name</h1>
            <p>Most duplications were generated by the formSite data entry. Typos and different spellings are not caught there. </p>
            <p>This list is one way to catch duplication in first and last names with different email address. Email is a key that does not allow dupliccation.</p>
            <p>Use this list to resolve dups by editing the incorrect record, moving the staff supervised if needed</p>
            <p>TODO list supervisees to transfer</p>
        </div>
        <p> {{ $this->closeChecker }}</p>
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

    @if($ownSuperNames)
        <div>
            <h1>Staff entered Own email for Supervisor email</h1>
        </div>
        <p> {{ $this->closeChecker }}</p>
        <table>
            <thead>
                <th>Id</th>
                <th>Staff Name</th>
                <th>Own Email</th>
                <th>Effective Date</th>
                <th>Supervisor Email</th>
                <th>Actions</th>
            </thead>

            <tbody>
                @foreach ( $ownSuperNames as $own )
                    <tr>
                        <td>{{ $own['user_id'] }} </td>
                        <td>{{ $own['user_name'] }} </td>
                        <td>{{ $own['email'] }} </td>
                        <td>{{ $own['effective'] }} </td>
                        <td>{{ $own['super'] }} </td>
                        <td> <a href="users/{{ $own['user_id'] }}/edit" target="_blank">Edit</a></td>
                    </tr>
                @endforeach
            </tbody>
        <table>
    @endif
    @if($unkSuperNames)
        <div>
            <h1>Staff entered unknown email for Supervisor email</h1>
        </div>
        <p> {{ $this->closeChecker }}</p>
        <table>
            <thead>
                <th>Id</th>
                <th>Staff Name</th>
                <th>Own Email</th>
                <th>Effective Date</th>
                <th>Supervisor Email</th>
                <th>Actions</th>
            </thead>

            <tbody>
                @foreach ( $unkSuperNames as $own )
                    <tr>
                        <td>{{ $own['user_id'] }} </td>
                        <td>{{ $own['user_name'] }} </td>
                        <td>{{ $own['email'] }} </td>
                        <td>{{ $own['effective'] }} </td>
                        <td>{{ $own['super'] }} </td>
                        <td> <a href="users/{{ $own['user_id'] }}/edit" target="_blank">Edit</a></td>
                    </tr>
                @endforeach
            </tbody>
        <table>
    @endif
</x-filament::page>
