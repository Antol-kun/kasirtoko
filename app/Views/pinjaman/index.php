<?= $this->extend('main/layout') ?>

<?= $this->section('judul') ?>
Manajemen Data Kasbon
<?= $this->endSection('judul') ?>

<?= $this->section('subjudul') ?>

<?= $this->endSection('subjudul') ?>


<?= $this->section('isi') ?>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row">
            <div class="col-lg-12">
                <button class="btn btn-info" data-toggle="modal" data-target="#modalTambah" id="tombolTambah">Tambah</button>
                <b class="ml-5">Menampilkan Pinjaman :</b>
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
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="judulForm">Tambah Pinjaman</h5>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">NIK</label>
                        <div class="col-sm-10">
                            <input oninput="cekData()" onchange="cekData()" type="text" class="form-control" id="nik" autocomplete="TRUE" list="daftarNasabah" placeholder="">
                            <datalist onchange="cekData()" id="daftarNasabah">

                            </datalist>
                            <div id="errorNasabah"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Nominal</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="nominal" name="nominal" placeholder="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Keterangan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="tambah()" id="simpan" disabled>Simpan</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalCicilan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="judulCicilan">Cicilan untuk pinjaman</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <input type="hidden" name="idPinjaman" id="idPinjaman">
                        <input type="number" class="form-control" id="nominalCicilan">
                    </div>
                    <div class="col-lg-6">
                        <button class="btn btn-info btn-sm" onclick="tambahCicilan()">Tambah</button>
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
                            <tbody id="tabelCicilan">
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
    muatNasabah()

    function tampilkan() {
        var baris = '<table class="table table-bordered" id="tabelPinjaman" width="100%" cellspacing="0"><thead><tr><th>NO</th><th>NIK</th><th>NAMA</th><th>TANGGAL</th><th>KETERANGAN</th><th>NOMINAL</th><th>CICILAN</th><th>PETUGAS</th><th>STATUS</th><th>CICILAN</th></tr></thead><tbody>'
        $.ajax({
            url: '<?= base_url() ?>/pinjaman/data',
            method: 'post',
            data: "lunas=" + $("#lunasBelum").val(),
            dataType: 'json',
            success: function(data) {
                for (let i = data.length - 1; i >= 0; i--) {
                    baris += "<tr><td>" + (data.length - i) + "</td><td>" + data[i].nik + "</td><td>" + data[i].nama + "</td><td>" + moment(data[i].tanggal).format("DD/MM/YY") + "</td><td>" + data[i].keterangan + "</td><td>" + data[i].nominal + "</td><td>" + data[i].cicilan + "</td><td>" + data[i].petugas + "</td><td>"
                    if (data[i].status == 1) {
                        baris += "Lunas"
                    } else {
                        baris += "Belum Lunas"
                    }
                    baris += "</td><td><button class='btn btn-info btn-sm' onClick='muatCicilan(" + data[i].id + ", \"" + data[i].nama + "\",\"" + data[i].keterangan + "\")'><i class='fa fa-eye'></i></td>"
                }

                baris += "</tbody></table>"
                $("#tempatTabel").html(baris)

                $('#tabelPinjaman').DataTable({
                    "pageLength": 10,
                });
            }
        });
    }

    function muatNasabah() {
        $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>/nasabah/dataAktif',
            dataType: 'json',
            success: function(data) {
                nasabah = data;
                var html = '';
                for (var i = 0; i < data.length; i++) {
                    html += '<option value="' + data[i].nik + '">' + data[i].nama + '</option>';;
                }
                $("#daftarNasabah").html(html);
            }
        });
    }

    function cekData() {
        $("#errorNasabah").html("")
        barangKosong = true;
        for (var i = 0; i < nasabah.length; i++) {
            if ($('#nik').val() == nasabah[i].nik) {
                $("#errorNasabah").html("<small class='text-success'>NIK ditemukan. atas Nama : '" + nasabah[i].nama + "'</small>")
                barangKosong = false;
                $("#nama").val(nasabah[i].nama)
                $("#simpan").prop("disabled", false)
                break
            }
        }

        if (barangKosong) {
            $("#errorNasabah").html("<small class='text-danger'>NIK tidak ditemukan.</small>")
            $('#nama').val("");
            $("#simpan").prop("disabled", true)
        }
    }

    function tambah() {
        if ($("#nominal").val() == "" || $("#nominal").val() == 0) {
            $("#nominal").focus();
        } else if ($("#keterangan").val() == "") {
            $("#keterangan").focus();
        } else {
            $.ajax({
                type: 'POST',
                data: 'nik=' + $("#nik").val() + '&nominal=' + $("#nominal").val() + '&keterangan=' + $("#keterangan").val(),
                url: '<?= base_url() ?>/pinjaman/tambah',
                dataType: 'json',
                success: function(data) {
                    $("#nik").val("");
                    $("#nama").val("");
                    $("#nominal").val("");
                    $("#keterangan").val("");

                    $('#modalTambah').modal('hide');
                    $("#errorNasabah").html("")
                    $("#simpan").prop("disabled", true)
                    tampilkan();
                }
            });
        }
    }

    function muatCicilan(idPinjaman, nama = "", keterangan = "") {
        if (nama) {
            $("#judulCicilan").html("Cicilan untuk : " + keterangan + "(" + nama + ")")
        }

        var baris = ""
        $.ajax({
            url: '<?= base_url() ?>/pinjaman/dataCicilan',
            method: 'post',
            data: "idPinjaman=" + idPinjaman,
            dataType: 'json',
            success: function(data) {
                if (data.length) {
                    for (let i = data.length - 1; i >= 0; i--) {
                        baris += "<tr><td>" + (data.length - i) + "</td><td>" + moment(data[i].tanggal).format("DD/MM/YY") + "</td><td>" + data[i].nominal + "</td><td>" + data[i].petugas + "</td><td>" + data[i].sisa + "</td>"
                    }
                } else {
                    baris += "<tr><td colspan='5' class='text-center'>Pinjaman ini belum dicicil :(</td></tr>"
                }

                $("#nominalCicilan").val("")
                baris += "</tbody></table>"
                $("#tabelCicilan").html(baris)
                $("#idPinjaman").val(idPinjaman)
                $("#modalCicilan").modal("show")

            }
        });
    }

    function tambahCicilan() {
        var idPinjaman = $("#idPinjaman").val()
        var nominalCicilan = $("#nominalCicilan").val()

        $.ajax({
            url: '<?= base_url() ?>/pinjaman/tambahCicilan',
            method: 'post',
            data: "idPinjaman=" + idPinjaman + "&nominal=" + nominalCicilan,
            dataType: 'json',
            success: function(data) {
                $("#nominalCicilan").val("")
                muatCicilan(idPinjaman)
                tampilkan()
            }
        }); 
    }
</script>


<?= $this->endSection('isi') ?>

<?= $this->section('script') ?>
<script src="<?= base_url() ?>/plugins/moment/moment.min.js"></script>
<?= $this->endSection('script') ?>