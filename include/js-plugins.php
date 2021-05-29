<!-- jQuery -->
<script src="./plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="./plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Main JS -->
<script src="./src/main.js"></script>
<!-- Bootstrap 4 -->
<script src="./plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="./plugins/chart.js/Chart.min.js"></script>
<!-- DataTables -->
<script src="./plugins/datatables/jquery.dataTables.min.js"></script>
<script src="./plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="./plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="./plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- JQVMap -->
<script src="./plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="./plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="./plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!-- InputMask -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
<!-- Datepicker -->
<script src='plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js' type='text/javascript'></script>
<!-- date-range-picker -->
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap color picker -->
<script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Bootstrap Switch -->
<script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- Sweet alert plugin -->
<script src="./dist/sweetalert/sweetalert2.min.js"></script>
<!-- Summernote -->
<script src="./plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="./plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="./dist/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="./dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="./dist/js/demo.js"></script>

<script>
  $(function(){
  $(".datepicker").datepicker({
      format: 'dd M yyyy',
      autoclose: true,
    });
  });

  var start = moment().subtract(-30, 'days');
  var end = moment();
  let startDate = $('.reservationtime').daterangepicker({
    timePicker: false, //<==MAKE THE CHANGE HERE
    singleDatePicker: true, //<==MAKE THE CHANGE HERE
    locale: {
      format: 'YYYY-MM-DD'
    }
  })

  var ctx = document.getElementById("reportChart").getContext('2d');
  var myChart = new Chart(ctx, {
			type: 'pie',
			data: {
        labels: [
          'Unlimited',
          'Kuota',
        ],
        datasets: [{
          label: 'Report Chart',
          data: [5, 2],
          backgroundColor: [
            'rgb(54, 162, 235)',
            'rgb(255, 205, 86)'
          ],
          hoverOffset: 4
        }]
			},
      options: {
        animation: {
            duration: 1000,
        },
      }
	});

  var ctx = document.getElementById("statisticOnline").getContext('2d');
  var myChart = new Chart(ctx, {
			type: 'line',
			data: {
        labels: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
        datasets: [{
          label: 'User',
          data: [13, 7, 10, 8, 5, 9, 15],
          fill: false,
          borderColor: 'rgb(17, 110, 179)',
          tension: 0.1
        }]
			},
      options: {
        animation: {
            duration: 1000,
        },
      }
	});
</script>