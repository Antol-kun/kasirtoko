<?= $this->extend('main/layout') ?>

<?= $this->section('judul') ?>
Manajemen Data User
<?= $this->endSection('judul') ?>

<?= $this->section('subjudul') ?>

<button type="button" class="btn btn-sm btn-primary btnAddUsers">
    <i class="fa fa-plus-circle"></i> Tambah Users
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

<table class="table table-sm table-bordered" id="datausers" style="width: 100%;">
    <thead>
        <tr>
            <th>No</th>
            <th>ID User</th>
            <th>Nama User</th>
            <th>Level</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
<div class="viewmodal" style="display: none;"></div>
<script>
$(document).ready(function() {
    dataUsers = $('#datausers').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= base_url()?>/users/listData'
        },
        fixedColumns: true,
        order: [],
        columns: [{
                data: 'nomor',
                orderable: false,
                width: 15
            },
            {
                data: 'userid',
            },
            {
                data: 'usernama'
            },
            {
                data: 'levelnama'
            },
            {
                data: 'statususer',
                width: 25
            },
            {
                width: 20,
                data: 'aksi',
                orderable: false
            }
        ]
    });
    $('.btnAddUsers').click(function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?= base_url()?>/users/formUsers",
            success: function(response) {
                $('.viewmodal').html(response).show();
                $('#modalAddUsers').on('shown.bs.modal', function(event) {
                    $('#iduser').focus();
                })
                $('#modalAddUsers').modal('show');
            }
        });
    });
    // $('#satuan').change(function(e) {
    //     e.preventDefault();
    //     dataBarang.ajax.reload();
    // });
});

function edit(userid) {
    $.ajax({
        type: "post",
        url: "<?= base_url()?>/users/formedit",
        data: {
            userid: userid
        },
        success: function(response) {
            $('.viewmodal').html(response).show();
            $('#modalEditUsers').on('shown.bs.modal', function(event) {
                $('#namalengkap').focus();
            })
            $('#modalEditUsers').modal('show');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + '\n' + thrownError);
        }
    });
}
</script>
<?= $this->endSection('isi') ?>