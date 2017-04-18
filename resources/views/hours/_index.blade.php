<table class="table table-striped">
    <thead>
    <tr>
        <th style="width: 100px">Date</th>
        @hasrole(['developer', 'teamlead', 'admin'])
        <th>Actual Hours</th>
        @endrole
        <th>Productive Hours</th>
        <th>Developer</th>
        <th>Details</th>
        @hasrole('developer')
        @else
            <th>Action</th>
        @endif
    </tr>
    </thead>
    <tbody>
    @foreach($hrs_details as $hrs)
    <tr id="tr_hours_{{$hrs->id}}">
        <td id="td_created_at_{{$hrs->id}}" style="width: 100px">{{$hrs->created_at->format('d-M')}}</td>
        @hasrole(['developer', 'teamlead', 'admin'])
        <td id="td_actual_hours_{{$hrs->id}}" >{{$hrs->actual_hours}}</td>
        @endrole
        <td id="td_productive_hours_{{$hrs->id}}" >{{$hrs->productive_hours}}</td>
        <td id="td_user_id_{{$hrs->id}}" >
        @foreach($users as $user)
            @if($hrs->user_id == $user->id )
                {{$user->name}}
            @endif
        @endforeach
        </td>
        <td id="td_details_{{$hrs->id}}" >{{$hrs->details}}</td>
        @hasrole('developer')
        @else
        <td class="link">
            <span class="glyphicon glyphicon-edit" id="hours_edit_{{$hrs->id}}" onclick="showform(this)"></span> | <span id="hours_delete_{{$hrs->id}}" class="glyphicon glyphicon-trash" onclick="delete_hour(this)"></span>
        </td>
        @endif
       
    </tr>
    <tr id="tr_hours_form_{{$hrs->id}}_1" class="hidden"></tr>
    <tr id="tr_hours_form_{{$hrs->id}}_2" class="hidden">
        
        <td style="width: 100px">{{$hrs->created_at->format('d-M')}}</td>
        @hasrole(['developer', 'teamlead', 'admin'])
        <td>
            <input type="number" class="form-control" name="actual-hours_{{$hrs->id}}" value="{{$hrs->actual_hours}}">
        </td>
        @endrole
        <td>
            <input type="number" class="form-control" name="productive-hours_{{$hrs->id}}" value="{{$hrs->productive_hours}}">
        </td>
        <td id="td_select">
            <select class="form-control" name="resource_{{$hrs->id}}">
                @foreach($users as $user)
                    <option value="{{$user['id']}}" @if($hrs->user_id == $user['id']) {{ "selected = selected " }} @endif>{{$user["name"]}}</option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="text" class="form-control" name="details_{{$hrs->id}}" value="{{$hrs->details}}">
        </td>
        <td class="link">
            <span id="hours_save_{{$hrs->id}}" class="glyphicon glyphicon-save" onclick="submitform(this)"></span> 
        </td>
    </tr>
    @endforeach
    </tbody>
</table>