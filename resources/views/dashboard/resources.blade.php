@hasrole(['sales', 'teamlead'])
<div class="col-md-12" style="position: fixed; bottom: 0px !important; background-color: #eee;z-index: 998;padding-bottom: 16px; display: inline-block; width: 120px; right: 0;">
    <div style="padding:10px 6px; margin: 0 -15px 10px -15px; text-align: center; background-color:#f4f4f4;"><strong>RESOURCES</strong></div>
    <div class="row">
        <div class="col-md-12 text-center">

         <div>Free: <a href="" data-toggle="modal" data-target="#myModal">  {{ $resources['free']->count() }}</a></div>

            <div>Allocated: <a href="" data-toggle="modal" data-target="#myModal2"> {{ $resources['allocated']->count() }} </a></div>

        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Free Resources Details</h4>
            </div>
            <div class="modal-body">
                @foreach($resources['free'] as $resource)
                {{$resource['name']}}
                <br>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<!-- Modal 2-->
<div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Allocated Resources Details</h4>
            </div>
            <div class="modal-body">
                @foreach($resources['allocated'] as $resource)
                    {{$resource['name']}}
                    <br>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

@endrole
