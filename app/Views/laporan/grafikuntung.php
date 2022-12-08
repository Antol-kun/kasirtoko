<link rel="stylesheet" href="<?= base_url() . '/plugins/chart.js/Chart.min.css' ?>">
<script src="<?= base_url() . '/plugins/chart.js/Chart.bundle.min.js' ?>"></script>

<canvas id="myChart" style="height: 50vh; width: 80vh;"></canvas>

<?php
$tanggal = "";
$total = "";

foreach ($grafik as $row) :
    $tgl = date('d-m-Y', strtotime($row->tgl));
    $tanggal .= "'$tgl'" . ",";

    $untung = $row->untung;
    $total .= "'$untung'" . ",";
endforeach;
?>

<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    console.log(ctx)
    // const fillPattern = ctx.createPattern(img, 'repeat');

    var chart = new Chart(ctx, {
        type: 'bar',
        responsive: true,
        data: {
            labels: [<?= $tanggal ?>],
            datasets: [{
                label: 'Total Untung',
                // backgroundColor: ['rgb(255,99,132)', 'rgb(14,99,132)', 'rgb(14,99,13)'],
                backgroundColor: 'rgb(14,99,13)',
                // borderColor: '',
                // backgroundColor: fillPattern,
                data: [<?= $total ?>]
            }],
        },
        duration: 1000
    })
</script>