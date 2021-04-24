<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Premier League</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"
          integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <!-- Styles -->
    <link rel="stylesheet" href={{asset('/assets/css/style.css')}}>
</head>

<body>
<div class="container ">

    <button id="start" class="btn btn-success" onclick="nextWeek()">Start Premiere League!</button>

    <div id="loader" class="loader d-none">
        Loading...
    </div>

    <div id="message"></div>


    <div id="table-cover" class="v-hidden">
       <div class="w-100">
           <h3>Teams Table</h3>
           <table id="table" class="table table-bordered table-hover d-none">
               <thead>
               <th>Team</th>
               <th>Point</th>
               <th>Win</th>
               <th>Equalization</th>
               <th>Lose</th>
               </thead>
               <tbody id="table-body">

               </tbody>
           </table>
       </div>

        <div class="w-100">
            <h3>Weekly Score</h3>
            <p id="week-description"></p>
            <table id="table" class="table table-bordered table-hover">
                <thead>
                <th>Team</th>
                <th>Point</th>
                <th>Point</th>
                <th>Team</th>
                </thead>
                <tbody id="results-body">

                </tbody>
            </table>
        </div>
    </div>

    <div class="actions">
        <button id="play-all" class="btn btn-secondary d-none" onclick="playAll()">Play All</button>
        <button id="next-week" class="btn btn-primary d-none" onclick="nextWeek()">Next Week</button>
    </div>
</div>


<script src="https://code.jquery.com/jquery-1.12.4.min.js"
        integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"
        integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd"
        crossorigin="anonymous"></script>

<script>

    function nextWeek() {
        $("#table-cover").removeClass('v-hidden');
        $("#loader").removeClass('d-none')
        $("#start").addClass('d-none')
        $("#message").innerText = "";

        $.get('/match/', (xhr, res, req) => {
            if (res === 'success' && req.status === 200) {
                $("#table-body").empty();
                $("#loader").addClass('d-none')
                $("#table").removeClass('d-none')
                $("#play-all").removeClass('d-none')
                $("#next-week").removeClass('d-none')

                // teams table
                xhr.calculated.map(e => {
                    $("#table-body").append(`
                            <tr>
                                <td>${e.team} </td>
                                <td>${e.point}</td>
                                <td>${e.won}</td>
                                <td>${e.equalization}</td>
                                <td>${e.lose}</td>
                            </tr> `)
                })

                //clear results table
                $("#results-body").empty();

                // render results
                xhr.match_results.map(matches => {
                    $("#week-description").text(matches[0].week + "th Match Results")
                    $("#results-body").append(`
                            <tr>
                                <td>${matches[0].team} </td>
                                <td>${matches[0].weekly_score}</td>
                                <td>${matches[1].weekly_score}</td>
                                <td>${matches[1].team}</td>
                            </tr> `)
                })

            } else {
                $("#loader").addClass('d-none')
                $("#message").innerText = "Failed the load League table"
            }
        })
    }

    function playAll() {
        $("#loader").removeClass('d-none')
        $.get('/result', (xhr, res, req) => {

            if (res === 'success' && req.status === 200) {

                $("#table-body").empty();
                $("#loader").addClass('d-none')

                xhr.map(e => {
                    $("#table-body").append(`
                            <tr>
                                <td>${e.team} </td>
                                <td>${e.point}</td>
                                <td>${e.won}</td>
                                <td>${e.equalization}</td>
                                <td>${e.lose}</td>
                            </tr> `)
                })

            } else {
                $("#loader").addClass('d-none')
                $("#message").innerText = "Failed the load League table"
            }
        })
    }
</script>

</body>
</html>
