<table class="table table-striped">
    <thead>
    <tr>
        <th style="width: 100px">Date</th>
        @hasrole(['developer', 'teamlead', 'admin'])
        <th>Actual Hours</th>
        @endrole
        <th>Productive Hours</th>
        <th>Details</th>
    </tr>
    </thead>
    <tbody>
    @foreach($hrs_details as $hrs)
    <tr>
        <td style="width: 100px">{{$hrs->created_at->format('d-M')}}</td>
        @hasrole(['developer', 'teamlead', 'admin'])
        <td>{{$hrs->actual_hours}}</td>
        @endrole
        <td>{{$hrs->productive_hours}}</td>
        <td>{{$hrs->details}}</td>
    </tr>
    @endforeach
    </tbody>
</table>