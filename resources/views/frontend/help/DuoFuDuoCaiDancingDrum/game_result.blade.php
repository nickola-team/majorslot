<!DOCTYPE html>
<html lang="en">

<head>
      <title>Game Record</title>
      <!--Import Google Icon Font-->
      <link type="text/css" rel="stylesheet" href="help/DuoFuDuoCai5Treasures/api/lib/Classes/materialize/css/material-icons.css" />
      <!--Import materialize.css-->
      <link type="text/css" rel="stylesheet" href="help/DuoFuDuoCai5Treasures/api/lib/Classes/materialize/css/materialize.cus.min.css" />
      <!--Import bootstrap.css-->
      <link rel="stylesheet" href="help/DuoFuDuoCai5Treasures/api/lib/Classes/bootstrap/4.2/css/bootstrap.min.css">
      <link rel="stylesheet" href="help/DuoFuDuoCai5Treasures/api/lib/base.css">
      <link rel="stylesheet" href="help/DuoFuDuoCai5Treasures/api/lib/Classes/datetimepicker/build/css/bootstrap-datetimepicker.min.css">
      <link rel="stylesheet" href="help/DuoFuDuoCai5Treasures/api/lib/Classes/fontawesome/css/fa-svg-with-js.css">
      <!--Import bootstrap datepicker.css-->
      <!-- <link rel="stylesheet" href="lib/Classes/bootstrap-4.0.0-dist/datetimepicker/build/css/bootstrap-datetimepicker.min.css"> -->

      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=0.3" />
      <meta charset="utf-8">
</head>

<body>
      <!--Import jQuery before materialize.js-->
      <script type="text/javascript" src="help/DuoFuDuoCai5Treasures/api/lib/Classes/Jquery/js/jquery-3.2.1.min.js"></script>
      <script type="text/javascript" src="help/DuoFuDuoCai5Treasures/api/lib/Classes/materialize/js/materialize.min.js"></script>
      <!-- <script src="lib/Classes/bootstrap/4.2/js/popper.1.12.9.min.js"></script> -->
      <script src="help/DuoFuDuoCai5Treasures/api/lib/Classes/bootstrap/4.2/js/bootstrap.min.js"></script>
      <!--Loading detail result with JQuery ajax-->
      <script src="help/DuoFuDuoCai5Treasures/api/lib/Classes/customize/game_result.js"></script>
      <!--Import monvemt before datetimepicker-->
      <script src="help/DuoFuDuoCai5Treasures/api/lib/Classes/monvent/monvent_locale.min.js"></script>
      <!--Import datepicker for datetime query-->
      <script src="help/DuoFuDuoCai5Treasures/api/lib/Classes/datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
      <script src="help/DuoFuDuoCai5Treasures/api/lib/Classes/fontawesome/js/fontawesome-all.min.js"></script>
      <!--Init dtPicker-->
      <script src="help/DuoFuDuoCai5Treasures/api/lib/Classes/customize/detail_of_game_record.js"></script>
</body>
<div class="container-fluid flow-text">
    <main id="main" col-12 col-md-9 col-xl-8 py-md-3 pl-md-5 bd-content" role="main">
          <div class="table-responsive">
                <table class="table table-hover">
                      <thead class="thead-dark">
                            <tr>
                                <th scope=col>Total bet</th>
                                <th scope=col>Total win</th>
                                <th scope=col>Net income</th>
                            </tr>
                      </thead>
                      <tbody>
                            <tr>
                                <td>{{number_format($totalbet, 2, ".", ",")}}</td>
                                <td>{{number_format($totalwin, 2, ".", ",")}}</td>
                                <td>{{number_format($totalwin - $totalbet, 2, ".", ",")}}</td>
                            </tr>
                      </tbody>
                </table>
          </div>
          <div class="table-responsive">
                <table class="table table-hover">
                      <thead class="thead-dark">
                            <tr>
                                  <th scope=col>지시사항</th>
                                  <th scope=col>GameName</th>
                                  <th scope=col>날짜 (UTC+8)</th>
                                  <th scope=col>배팅</th>
                                  <th scope=col>승</th>
                                  <th scope=col>메인</th>
                                  <th scope=col>보너스</th>
                                  <th scope=col>잭팟</th>
                                  <th scope=col>게임결과</th>
                            </tr>
                      </thead>
                      <tbody>
                           
                            @foreach($records AS $index=>$record)
                                <tr>
                                    <td>{{$record->id}}</td>
                                    <td>1c dafudacai-DanncingDrum</td>
                                    <td>{{$record->date_time}}</td>
                                    <td>{{$record->bet}}</td>
                                    <td>{{$record->win}}</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td><button type="button" id="btn_637468790987037550_111407"
                                        class="btn btn-detail waves-light" data-toggle="modal"
                                        data-target=#modal_637468790987037550_111407><i
                                              class="material-icons">view_module</i></button></td>
                                </tr>
                            @endforeach
                      </tbody>
                </table>
          </div>
</div>
<nav class="navbar-dark bg-dark" aria-label="Page navigation">
    <ul class="pagination pagination-lg">
          <li class="page-item"><a class="page-link"
                      href="?game_id=111407&lang=ko-kr&gameType=DuoFuDuoCai5Treasures&page=0"
                      aria-label="Previous"><span aria-hidden="true">&laquo;</span><span
                            class="sr-only">Previous</span></a></li>
          <li class="page-item active" href="?game_id=111407&lang=ko-kr&gameType=DuoFuDuoCai5Treasures&page=0"><span
                      class="page-link">1<span class="sr-only">(current)</span></a></li>
          <li class="page-item"><a class="page-link"
                      href="?game_id=111407&lang=ko-kr&gameType=DuoFuDuoCai5Treasures&page=0" aria-label="Next"><span
                            aria-hidden="true">&raquo;</span><span class="sr-only">Next</span></a></li>
    </ul>
</nav>
</div>

</html>