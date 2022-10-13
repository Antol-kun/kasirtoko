<?= $this->extend('main/layout') ?>

<?= $this->section('judul') ?>
Manajemen Data Barang
<?= $this->endSection('judul') ?>

<?= $this->section('subjudul') ?>

<button type="button" class="btn btn-sm btn-primary" onclick="location.href=('/barang/tambah')">
    <i class="fa fa-plus-circle"></i> Tambah Barang
</button>

<?= $this->endSection('subjudul') ?>


<?= $this->section('isi') ?>
<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url() ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<!-- DataTables  & Plugins -->
<script src="<?= base_url() ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url() ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<div class="form-group">
    <label for="">Filter Satuan</label>
    <select name="satuan" id="satuan" class="form-control form-control-sm">
        <option value="">-Pilih-</option>
        <?php foreach ($datasatuan as $row) : ?>
        <option value="<?= $row['satid'] ?>"><?= $row['satnama'] ?></option>
        <?php endforeach; ?>
    </select>
</div>

<table class="table table-sm table-bordered" id="databarang" style="width: 100%;">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Satuan</th>
            <th>Harga</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<script>
$(document).ready(function() {
    dataBarang = $('#databarang').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/barang/listData',
            data: function(d) {
                d.satuan = $('#satuan').val()
            }
        },
        order: [],
        columns: [{
                data: 'nomor',
                orderable: false
            },
            {
                data: 'brgkode',
            },
            {
                data: 'brgnama'
            },
            {
                data: 'katnama'
            },
            {
                data: 'satnama'
            },
            {
                data: 'brgharga'
            },
            {
                data: 'aksi'
            }
        ]
    });

    $('#satuan').change(function(e) {
        e.preventDefault();
        dataBarang.ajax.reload();
    });
});

function edit(kode) {
    window.location.href = ('/barang/edit/' + kode);
}

function hapus(kode) {
    Swal.fire({
        title: 'Hapus Barang',
        html: `Yakin data barang dengan kode <strong>${kode}</strong> di hapus ?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya,hapus !',
        cancelButtonText: 'Tidak'
    });
}
</script>
<?= $this->endSection('isi') ?>