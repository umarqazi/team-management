<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Techverx</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

    <!-- Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" />
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{URL::asset('css/task_modal.css')}}">
    <link rel="stylesheet" href="{{URL::asset('css/custom.css')}}">
    @yield('styles')
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
        .no_button {
            background: none;
            border: none;
            color: #337ab9;
            padding: 0px !important;
        }
        .no_button:hover {
            color: #23527c !important;
            text-decoration: underline;
            font-weight: 400 !important;
        }
        .link{
            color: #337ab7 !important;
        }
        .link:hover{
            color: #23527c !important;
        }
        .canvasjs-chart-credit {
            display: none;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="{{URL::asset('js/login.js')}}"></script>
    @yield('scripts')
</head>
<body id="app-layout">
<div class="main_wrapper">
<nav class="navbar navbar-default navbar-static-top menuBar">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                Techverx
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                @if (Auth::guest())
                    <li><a href="{{ url('/home') }}">Home</a></li>
                @else
                    <li><a href="{{ url('/home') }}">Home</a></li>
                    <li><a href="{{ url('/projects') }}">Projects</a></li>
                @endif
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">Login</a></li>
                    <li><a href="{{ url('/register') }}">Register</a></li>
                @else

                    @if(auth()->user()->can('create user'))
                        <li><a href="{{ url('/users') }}">Users</a></li>
                    @endif

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/users/'.Auth::user()->id.'/edit') }}"><i class="fa fa-btn fa-user"></i>Profile</a></li>
                            <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

@if(Session::has('msgerror'))
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="alert {{ Session::get('alert-class', 'alert-info') }}">
                    {{ Session::get('msgerror') }}
                </div>
            </div>
        </div>
    </div>
@endif

@yield('content')
</div>
<footer class="footer">
    <!--Copyright-->
    <div class="text-right">
        TM {{env('TM_VERSION_NO')}}
    </div>
    <!--/.Copyright-->
</footer>


<!-- JavaScripts -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
{{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
<script src="http://canvasjs.com/assets/script/canvasjs.min.js"></script>

<script>

    var technologies    = [{id: "PHP", text: 'PHP'}, {id: "Ruby on Rails", text: 'Ruby on Rails'}, {id: "Laravel", text: 'Laravel'}, {id: "Codeigniter", text: 'Codeigniter'}, {id: "iOS", text: 'iOS'}, {id: "Android", text: 'Android'}, {id: "Node.JS", text: "Node.JS"}, {id: "React.JS", text: "React.JS"}, {id: "Angular.JS", text: "Angular.JS"}, {id: "Wordpress", text: "Wordpress"}, {id: "Magento", text: "Magento"}];
    var AllProjects = ['Actionable Insights Web','ActionableInsights','Amazing you cleaning','Caribbean Charter','Cocomo Realty','Crisis Hub'];
    $(document).ready(function(){
        $('select.form-control[name^="technology"]').select2({
            placeholder: "Select Technologies",
            data: technologies
        });
    });

    $(document).ready(function(){
        $('#project-charts').select2({
            theme: "classic"
        });
        $('#project-resource').select2({
            theme: "classic"
        });
    });
    $(document).ready(function(){
        $('select.form-control[name^="teamlead"]').select2({
            placeholder: "Select Teamlead(s)"
        });
    });

    $(document).ready(function(){
        $('select.form-control[name^="developer"]').select2({
            placeholder: "Select Developer(s)"
        });
    });

    $(document).ready(function() {
        $('#myTable').dataTable({
            "lengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]]
        });
    });
    $(document).ready(function(){
        $('#myTable2').dataTable({
            "lengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]]
        });
    });
    $(document).on("change","#td_select select",function(){
      $("option[value=" + this.value + "]", this)
      .attr("selected", true).siblings()
      .removeAttr("selected")
    });
    function showform(elem) {
        var id = $(elem).attr("id");
        var res = id.split("_");
        id = res[2];
        document.getElementById("tr_hours_"+id).classList.add("hidden");
        document.getElementById("tr_hours_form_"+id+"_1").classList.remove("hidden");
        document.getElementById("tr_hours_form_"+id+"_2").classList.remove("hidden");
    }
    function delete_hour(elem) {
        var id = $(elem).attr("id");
        var res = id.split("_");
        id = res[2];
        var $_token = "{{ csrf_token() }}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $_token
            }
        });
        $.ajax({
            url : "/hour/delete/"+id,
            type: 'POST',
            cache: false,
            success: function(response){
                $('#tr_hours_'+id).addClass("hidden");
            }
        });
    }
    function submitform(elem) {
        var id = $(elem).attr("id");
        var res = id.split("_");
        id = res[2];
        var created_at          = $('input[name=created_at_'+id+']').val();
        var actual_hours        = $('input[name=actual-hours_'+id+']').val();
        var productive_hours    = $('input[name=productive-hours_'+id+']').val();
        var user_id             = $('select[name=resource_'+id+']').val();
        var details             = $('input[name=details_'+id+']').val();
        var $_token             = "{{ csrf_token() }}";
        var data                = { actual_hours    : actual_hours,
                                    productive_hours: productive_hours,
                                    resource        : user_id,
                                    details         : details,
                                    created_at      : created_at
                                   };
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $_token
            }
        });
        $.ajax({
            url : "/hour/update/"+id,
            type: 'POST',
            data: data,
            cache: false,
            success: function(response){
                var d = new Date(response.hours.created_at);
                var months_name = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
                $("#td_actual_hours_"+id).html(response.hours.actual_hours);
                $("#td_productive_hours_"+id).html(response.hours.productive_hours);
                $("#td_user_id_"+id).html(response.hours.user_name);
                $("#td_details_"+id).html(response.hours.details);
                $("#td_created_at_"+id).html(response.hours.createDate);
                $('#tr_hours_'+id).removeClass("hidden");
                $('#tr_hours_form_'+id+'_1').addClass("hidden");
                $('#tr_hours_form_'+id+'_2').addClass("hidden");
            }
        });
    }


    function generateKeySuggestions() {
        // Code To Generate Key Suggestions For Project Starts
        var num = Math.floor(Math.random() * 90000) + 10000;
        var alphaNumericKey = key + num;
        document.getElementById('ProjectKeySugg1').innerHTML = alphaNumericKey;
        // Code To Generate Key Suggestions For Project Ends
    }

    function generateKeyForAllProjects(){
        alert('Generate Function');
        var projectArray = [];
        var len = AllProjects.length;
        for (var i=0;i<len; i++){
            alert('Generate Function For Loop:'+ i);

            if (hasWhiteSpace(AllProjects[i]))
            {
                var matches = AllProjects[i].match(/\b(\w)/g);
                projectArray[i] = matches.join('').toUpperCase();
                alert(projectArray[i]);
            }
            else {
                projectArray[i] = AllProjects[i];
                alert(projectArray[i]);
            }
        }
        alert(projectArray);
    }

    function hasWhiteSpace(string) {
        if(/\s/g.test(string))
        {
         return true;
        }
        return false;
    }

    // Function To Show add Task Modal-Form-Input-Fields on Input Check Starts
    function stateChanged(id){
        if ($('#'+id).is(":checked")){
            $("."+id).show();
        }

        else
            $("."+id).hide();

    }
    // Function To Show add Task Modal-Form-Input-Fields on Input Check Ends

    $('.dropdown-menu input, .dropdown-menu label').click(function(e) {
        e.stopPropagation();
    });

    $(document).ready(function() {
        $('#inactive_projects').DataTable();
    } );
    $(document).ready(function() {
        $('#active_projects').DataTable();
    });
</script>
</body>
</html>
