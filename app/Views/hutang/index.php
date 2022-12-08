<?= $this->extend('main/layout') ?>

<?= $this->section('judul') ?>
Manajemen Data Hutang
<?= $this->endSection('judul') ?>

<?= $this->section('subjudul') ?>

<?= $this->endSection('subjudul') ?>


<?= $this->section('isi') ?>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row">
            <div class="col-lg-12">
                <b>Menampilkan Hutang :</b>
                <select name="lunasBelum" id="lunasBelum" class="btn btn-outline-info" onchange="tampilkan()">
                    <option value="0">Belum Lunas</option>
                    <option value="1">Lunas</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive" id="tempatTabel">

        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalAngsuran" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="judulAngsuran">Angsuran untuk hutang</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <input type="hidden" name="hutangid" id="hutangid">
                        <input type="number" class="form-control" id="nominalAngsuran">
                    </div>
                    <div class="col-lg-6">
                        <button class="btn btn-info btn-sm" onclick="tambahAngsuran()">Tambah</button>
                    </div>
                </div>
                <div class="row pt-3">
                    <div class="col-12">
                        <table class="table table-bordered" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nominal</th>
                                    <th>Petugas</th>
                                    <th>Sisa</th>
                                </tr>
                            </thead>
                            <tbody id="tabelAngsuran">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    tampilkan()

    function tampilkan() {
        var baris = '<table class="table table-bordered" id="tabelHutang" width="100%" cellspacing="0"><thead><tr><th>NO</th><th>ID Pelanggan</th><th>NAMA</th><th>JUMLAH TRX</th><th>HUTANG</th><th>ANGSURAN</th><th>SISA</th><th>DETAIL</th></tr></thead><tbody>'
        $.ajax({
            url: '<?= base_url() ?>/hutang/data',
            method: 'post',
            data: "lunas=" + $("#lunasBelum").val(),
            dataType: 'json',
            success: function(data) {
                for (let i = data.length - 1; i >= 0; i--) {
                    baris += "<tr><td>" + (data.length - i) + "</td><td>" + data[i].pelid + "</td><td>" + data[i].pelnama + "</td><td>" + data[i].trx + "</td><td>" + data[i].sumnom + "</td><td>" + data[i].sumangs + "</td><td>" + data[i].sisa + "</td>"
                    baris += "<td><button class='btn btn-info btn-sm' onClick=\"detail(" + data[i].pelid + ", '" + data[i].pelnama + "')\"><i class='fa fa-eye'></i></button></td>"
                }

                baris += "</tbody></table>"
                $("#tempatTabel").html(baris)

                $('#tabelHutang').DataTable({
                    "pageLength": 10,
                });
            }
        })
    }

    function detail(id, nama) {
        var baris = '<button class="btn btn-info btn-sm" onClick="tampilkan();"><i class="fa fa-arrow-left"></i> Kembali</button><br>Nama Pelanggan : <b>'+nama+'</b><table class="table table-bordered" id="tabelHutang" width="100%" cellspacing="0"><thead><tr><th>NO</th><th>TANGGAL</th><th>TEMPO</th><th>FAKTUR</th><th>NOMINAL</th><th>ANGSURAN</th><th>KETERANGAN</th><th>STATUS</th><th>DETAIL</th></tr></thead><tbody>'
        $.ajax({
            url: '<?= base_url() ?>/hutang/dataDetail',
            method: 'post',
            data: {
                "lunas": $("#lunasBelum").val(),
                "pelid": id,
                "pelnama": nama
            },
            dataType: 'json',
            success: function(data) {
                for (let i = data.length - 1; i >= 0; i--) {
                    tanggal = moment(data[i].tanggal)
                    tanggaltempo = moment(tanggal).add(parseInt(data[i].tempo_hutang), 'M')
                    difnow = (tanggaltempo.diff(moment()))

                    baris += "<tr><td>" + (data.length - i) + "</td><td>" + tanggal.format("DD/MM/YY") + "</td><td>" + tanggaltempo.format("DD/MM/YY") + "</td><td>" + data[i].faktur + "</td><td>" + data[i].nominal + "</td><td>" + data[i].angsuran + "</td><td>" + data[i].ket + "</td><td>"
                    if (data[i].status == 1) {
                        baris += "<span class=\"badge bg-green\">Lunas</span>"
                    } else {
                        if (difnow < 0) {
                            baris += "<span class=\"badge bg-red\">Belum Lunas <br> Tempo Terlewati</span>"
                        } else {
                            baris += "<span class=\"badge bg-orange\">Belum Lunas</span>"
                        }
                    }
                    baris += "</td><td><button class='btn btn-info btn-sm' onClick='muatAngsuran(" + data[i].id + ", \"" + data[i].pelnama + "\",\"" + data[i].faktur + "\")'><i class='fa fa-eye'></i></button></td>"
                }

                baris += "</tbody></table>"
                $("#tempatTabel").html(baris)

                $('#tabelHutang').DataTable({
                    "pageLength": 10,
                });
            }
        });
    }

    function muatAngsuran(hutangid, pelnama = "", faktur = "") {
        if (pelnama) {
            $("#judulAngsuran").html("Angsuran untuk faktur : " + faktur + " (" + pelnama + ")")
        }

        var baris = ""
        $.ajax({
            url: '<?= base_url() ?>/hutang/dataAngsuran',
            method: 'post',
            data: "hutangid=" + hutangid,
            dataType: 'json',
            success: function(data) {
                if (data.length) {
                    for (let i = data.length - 1; i >= 0; i--) {
                        baris += "<tr><td>" + (data.length - i) + "</td><td>" + moment(data[i].tanggal).format("DD/MM/YY") + "</td><td>" + data[i].nominal + "</td><td>" + data[i].petugas + "</td><td>" + data[i].sisa + "</td>"
                    }
                } else {
                    baris += "<tr><td colspan='5' class='text-center'>Hutang ini belum diangsur :(</td></tr>"
                }

                $("#nominalAngsuran").val("")
                baris += "</tbody></table>"
                $("#tabelAngsuran").html(baris)
                $("#hutangid").val(hutangid)
                $("#modalAngsuran").modal("show")

            }
        });
    }

    function tambahAngsuran() {
        var hutangid = $("#hutangid").val()
        var nominalAngsuran = $("#nominalAngsuran").val()

        $.ajax({
            url: '<?= base_url() ?>/hutang/tambahAngsuran',
            method: 'post',
            data: "hutangid=" + hutangid + "&nominal=" + nominalAngsuran,
            dataType: 'json',
            success: function(data) {
                $("#nominalAngsuran").val("")
                muatAngsuran(hutangid)
                tampilkan()
            }
        });
    }
</script>


<?= $this->endSection('isi') ?>

<?= $this->section('script') ?>
<script src="<?= base_url() ?>/plugins/moment/moment.min.js"></script>
<?= $this->endSection('script') ?>