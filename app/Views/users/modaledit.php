<div class="modal fade" id="modalEditUsers" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Data User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('users/update', ['class' => 'frmsimpan']); ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">ID User</label>
                    <input type="text" autocomplete="off" name="iduser" id="iduser" class="form-control form-control-sm"
                        value="<?= $iduser; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="">Nama Lengkap</label>
                    <input type="text" autocomplete="off" name="namalengkap" id="namalengkap"
                        class="form-control form-control-sm" value="<?= $namalengkap; ?>">
                </div>
                <div class="form-group">
                    <label for="">Levels</label>
                    <select class="form-control form-control-sm" name="level" id="level">
                        <?php foreach ($datalevel->getResultArray() as $l) : ?>
                        <?php if ($level == $l['levelid']) : ?>
                        <option selected value="<?= $l['levelid']; ?>"><?= $l['levelnama']; ?></option>
                        <?php else : ?>
                        <option value="<?= $l['levelid']; ?>"><?= $l['levelnama']; ?></option>
                        <?php endif; ?>

                        <?php endforeach; ?>
                    </select>
                    <div id="msg-level" class="invalid-feedback">
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Status User :</label>
                    <?php
                    if ($stt == '1')
                        echo "<span class='badge badge-success'>Active</span>";
                    else
                        echo "<span class='badge badge-danger'>Non Active</span>";
                    ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-sm btn-success btnsimpan">Update</button>
                <button type="button" class="btn btn-sm btn-info btnUbahStatus"><i class="fa fa-edit"></i>
                    Ubah Status</button>
                <button type="button" class="btn btn-sm btn-danger btnHapusUser">
                    <i class="fa fa-trash-alt"></i> Hapus User
                </button>
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('.frmsimpan').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            beforeSend: function() {
                $('.btnsimpan').prop('disabled', true);
                $('.btnsimpan').html('<i class="fa fa-spin fa-spinner"></i>');
            },
            complete: function() {
                $('.btnsimpan').prop('disabled', false);
                $('.btnsimpan').html('Update');
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    html: response.sukses
                });
                $('#modalEditUsers').modal('hide');
                dataUsers.ajax.reload();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + '\n' + thrownError);
            }
        });
        return false;
    });

    $('.btnUbahStatus').click(function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Ubah Status User',
            text: "Yakin di update ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Update !',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "post",
                    url: "/users/updateStatus",
                    data: {
                        iduser: $('#iduser').val()
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.sukses) {
                            Swal.fire('Berhasil', response.sukses, 'success');
                            $('#modalEditUsers').modal('hide');
                            dataUsers.ajax.reload();
                        }
                    }
                });
            }
        })
    });
    $('.btnHapusUser').click(function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Hapus User',
            html: `Yakin menghapus ID User = ${$('#iduser').val()} ?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus !',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "post",
                    url: "/users/hapusUser",
                    data: {
                        iduser: $('#iduser').val()
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.sukses) {
                            Swal.fire('Berhasil', response.sukses, 'success');
                            $('#modalEditUsers').modal('hide');
                            dataUsers.ajax.reload();
                        }
                    }
                });
            }
        })
    });
});
</script>