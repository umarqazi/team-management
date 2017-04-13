<table class="table table-striped">
    <thead>
    <tr>
        <th style="width: 100px">Date</th>
        @hasrole(['developer', 'teamlead', 'admin'])
        <th>Actual Hours</th>
        @endrole
        <th>Productive Hours</th>
        <th>Details</th>
        <th>Edit</th>
    </tr>
    </thead>
    <tbody>
    @foreach($hrs_details as $hrs)
    <tr id="tr_hours_{{$hrs->id}}">
        <td style="width: 100px">{{$hrs->created_at->format('d-M')}}</td>
        @hasrole(['developer', 'teamlead', 'admin'])
        <td>{{$hrs->actual_hours}}</td>
        @endrole
        <td>{{$hrs->productive_hours}}</td>
        <td>{{$hrs->details}}</td>
        <td class="link"><span class="glyphicon glyphicon-edit" id="hours_edit_{{$hrs->id}}" onclick="showform(this)"></span></td>
    </tr>
    <tr id="tr_hours_form_{{$hrs->id}}_1" class="hidden"></tr>
    <tr id="tr_hours_form_{{$hrs->id}}_2" class="hidden">
        <input type="hidden" name="token[{{$hrs->id}}]" value="{{ csrf_token() }}">
        <td style="width: 100px">{{$hrs->created_at->format('d-M')}}</td>
        @hasrole(['developer', 'teamlead', 'admin'])
        <td><input type="number" class="form-control" name="actual-hours[{{$hrs->id}}]" value="{{$hrs->actual_hours}}"></td>
        @endrole
        <td><input type="number" class="form-control" name="productive-hours[{{$hrs->id}}]" value="{{$hrs->productive_hours}}"></td>
        <td><input type="text" class="form-control" name="details[{{$hrs->id}}]" value="{{$hrs->details}}"></td>
        <td class="link"><span id="hours_save_{{$hrs->id}}" class="glyphicon glyphicon-save" onclick="submitform(this)"></span></td>
    </tr>
        </form>
    @endforeach
    </tbody>
</table>