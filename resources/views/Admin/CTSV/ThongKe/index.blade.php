@extends('admin_layout')
@section('admin_content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary text-center">Thống kê top 5 học bổng có lượt xem nhiều nhất</h6>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-center">
            <div id="piechart" style="width: 1000px; height: 500px;"></div>
        </div>
        <div class="table-responsive" style="margin-top: 20px;">
            <table class="table table-bordered" id="loaihocbong_table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên học bổng</th>
                        <th>Lượt xem</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hocbong as $key=> $hocbong)
                    <tr>
                        <td>{{ $key + 1}}</td>
                        <td>{{$hocbong->hocbong_ten}}</td>
                        <td>{{$hocbong->hocbong_luotxem}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Tên học bổng', 'Tổng số lượt xem'],
            <?php echo $chartData; ?>
        ]);

        var options = {
            title: 'TOP 5 HỌC BỔNG CÓ LƯỢT XEM NHIỀU NHẤT',
            is3D: true,
            titleTextStyle: {
                color: "#000",
                fontName: "Roboto",
                fontSize: 17,
                bold: true,
            }
        };
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
    }
</script>
@endsection