@hasrole(['sales', 'teamlead'])
<div class="col-md-12" style="margin-top: 20px; margin-bottom: 40px;">
    <div class="row">
        <div class="col-md-6">
            <table id="myTable" class="table table-striped table-bordered" cellspacing="0"
                   width="100%">
                <thead>
                <tr>
                    <th>Allocated Resources</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Allocated Resources</th>
                </tr>
                </tfoot>
                <tbody>
                @foreach($resources['allocated'] as $resource)

                    <tr>
                        <td>{{$resource['name']}}</td>

                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>

        <div class="col-md-6">

            <table id="myTable2" class="table table-striped table-bordered" cellspacing="0"
                   width="100%">
                <thead>
                <tr>
                    <th>Free Resources</th>

                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Free Resources</th>

                </tr>
                </tfoot>
                <tbody>
                @foreach($resources['free'] as $resource)

                    <tr>
                        <td>{{$resource['name']}}</td>

                    </tr>
                @endforeach


                </tbody>
            </table>
        </div>

    </div>
</div>
</div>
@endrole
