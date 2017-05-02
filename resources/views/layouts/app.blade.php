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

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" />
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
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
</head>
<body id="app-layout">
<nav class="navbar navbar-default navbar-static-top">
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
                    @hasrole('admin')
                    <li><a href="{{ url('/users') }}">Users</a></li>
                    @endrole
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

@yield('content')

<footer style="margin-top: 50px; height: 100px;"></footer>


<!-- JavaScripts -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
{{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
<script src="http://canvasjs.com/assets/script/canvasjs.min.js"></script>

<script>

    var technologies    = [{id: "PHP", text: 'PHP'}, {id: "Ruby on Rails", text: 'Ruby on Rails'}, {id: "iOS", text: 'iOS'}, {id: "Android", text: 'Android'}, {id: "Node.JS", text: "Node.JS"}, {id: "React.JS", text: "React.JS"}, {id: "Angular.JS", text: "Angular.JS"}, {id: "Wordpress", text: "Wordpress"}, {id: "Magento", text: "Magento"}];

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
        $('select.form-control[name="teamlead"]').select2({
            placeholder: "Select Teamlead",
            theme: "classic"
        });
    });

    $(document).ready(function(){
        $('select.form-control[name^="developer"]').select2({
            placeholder: "Select Developers"
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
    function submitform(elem) {
        var id = $(elem).attr("id");
        var res = id.split("_");
        id = res[2];
        console.log(id);
        var actual_hours = parseInt($('input[name=actual-hours_'+id+']').val());
        var productive_hours = parseInt($('input[name=productive-hours_'+id+']').val());
        var user_id = $('select[name=resource_'+id+']').val();
        console.log(user_id);
        var details = $('input[name=details_'+id+']').val();
        var $_token = "{{ csrf_token() }}";
        var data = { actual_hours: actual_hours,
                     productive_hours: productive_hours,
                     resource: user_id,
                     details: details
                 };
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $_token
                // 'ContentType' : 'application/x-www-form-urlencoded',
                // 'Accept'      : 'application/json'
            }
        });
        $.ajax({
            url : "/hour/update/"+id,
            type: 'POST',
            //data: {id: "10"},
            data: data,
            cache: false,
            //dataType: 'json',
            //contentType: 'charset=UTF-8',
            // processData: false,
            success: function(response){
                console.log(response);
                $("#td_actual_hours_"+id).html(response.hours.actual_hours);
                $("#td_productive_hours_"+id).html(response.hours.productive_hours);
                $("#td_user_id_"+id).html(response.hours.user_name);
                $("#td_details_"+id).html(response.hours.details);
                $('#tr_hours_'+id).removeClass("hidden");
                $('#tr_hours_form_'+id+'_1').addClass("hidden");
                $('#tr_hours_form_'+id+'_2').addClass("hidden");
            }
        });
    }

    $(document).ready(function() {
        $('#eng_projects').DataTable();
    } );
    $(document).ready(function() {
        $('#inactive_projects').DataTable();
    } );
    $(document).ready(function() {
        $('#active_projects').DataTable();
    } );
</script>
</body>
</html>
