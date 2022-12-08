<script src="<?= base_url('dist/js/autoNumeric.js') ?>"> </script>
<div class="modal fade" id="modalpembayaran" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Pembayaran Faktur Hutang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('barangkeluar/simpanPembayaran', ['class' => 'frmpembayaran']) ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">No.Faktur</label>
                    <input type="text" name="nofaktur" id="nofaktur" class="form-control" value="<?= $nofaktur; ?>" readonly>
                    <input type="hidden" name="tglfaktur" value="<?= $tglfaktur; ?>">
                    <input type="hidden" name="idpelanggan" value="<?= $idpelanggan; ?>">
                    <input type="hidden" name="paymentmethod" value="<?= $paymentmethod; ?>">
                </div>
                <div class="form-group">
                    <label for="">Nama Pelanggan</label>
                    <input type="text" name="namapelanggan" id="namapelanggan" class="form-control" value="<?= $namapelanggan; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="">Total Hutang</label>
                    <input type="text" name="totalbayar" id="totalbayar" class="form-control" value="<?= $totalharga; ?>" readonly>
                </div>
                <!-- <div class="form-group hidden"> -->
                <!-- <label for="">Diskon (%)</label> -->
                <input type="hidden" name="dispersen" id="dispersen" class="form-control" autocomplete="false">
                <!-- </div> -->
                <!-- <div class="form-group hidden"> -->
                <!-- <label for="">Diskon (Rp)</label> -->
                <input type="hidden" name="disuang" id="disuang" class="form-control" autocomplete="false">
                <!-- </div> -->
                <!-- <div class="form-group hidden"> -->
                <!-- <label for="">Jumlah Uang</label> -->
                <!-- <input type="hidden" name="jumlahuang" id="jumlahuang" class="form-control" autocomplete="false"> -->
                <!-- <input type="hidden" value="0" name="jumlahuang" id="jumlahuang" class="form-control" autocomplete="false" readonly> -->
                <!-- </div> -->
                <!-- <div class="form-group hidden"> -->
                <!-- <label for="">Sisa Uang</label> -->
                <!-- <input type="hidden" name="sisauang" id="sisauang" class="form-control" readonly> -->
                <input type="hidden" value="0" name="sisauang" id="sisauang" class="form-control" readonly>
                <!-- </div> -->

                <div class="form-group">
                    <label for="">Tempo Hutang</label>
                    <select name="tempohutang" id="tempohutang" class="form-control" required>
                        <option value="">-- Silakan Pilih Tempo Hutang</option>
                        <option value="1">1 bulan</option>
                        <option value="3">3 bulan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" class="form-control">Keterangan</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btnsimpan">
                    Simpan
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>
<script src="<?= base_url('dist/js/AutoNumeric.js') ?>"></script>
<script>
    $(document).ready(function() {
        // $('#totalbayar').autoNumeric('init', {
        //     mDec: 0,
        //     aDec: ',',
        //     aSep: '.'
        // });
        // $('#jumlahuang').autoNumeric('init', {
        //     mDec: 0,
        //     aDec: ',',
        //     aSep: '.'
        // });
        $('#dispersen').autoNumeric('init', {
            mDec: 2,
            aDec: ',',
            aSep: '.'
        });
        $('#disuang').autoNumeric('init', {
            mDec: 0,
            aDec: ',',
            aSep: '.'
        });
        $('#sisauang').autoNumeric('init', {
            mDec: 0,
            aDec: ',',
            aSep: '.'
        });

        // $('#dispersen').keyup(function (e) {
        //     hitungDiskon()
        // });
        // $('#disuang').keyup(function (e) {
        //     hitungDiskon()
        // });
        $('#jumlahuang').keyup(function(e) {
            hitungDiskon()
        });

        function hitungDiskon() {

            let totalbayar = $('#totalbayar').val();
            let jumlahuang = $('#jumlahuang').val();
            let dispersen = ($('#dispersen').val() == "") ? 0 : $('#dispersen').autoNumeric('get');
            let disuang = ($('#disuang').val() == "") ? 0 : $('#disuang').autoNumeric('get');

            let hasil, total;
            hasil = parseFloat(totalbayar) - (parseFloat(totalbayar) * parseFloat(dispersen) / 100) - parseFloat(disuang);
            total = parseFloat(jumlahuang) - hasil;
            console.log(hasil)
            $('#sisauang').val(total);
        }

        // $('#jumlahuang').keyup(function(e) {
        //     let totalbayar = $('#totalbayar').autoNumeric('get');
        //     let jumlahuang = $('#jumlahuang').autoNumeric('get');

        //     let sisauang;

        //     if (parseInt(jumlahuang) < parseInt(totalbayar)) {
        //         sisauang = 0;
        //     } else {
        //         sisauang = parseInt(jumlahuang) - parseInt(totalbayar);
        //     }

        //     $('#sisauang').autoNumeric('set', sisauang);
        // });

        $('.frmpembayaran').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function() {
                    $('.btnsimpan').prop('disabled', true);
                    $('.btnsimpan').html('<i class="fa fa-spin fa-spinner"></i>')
                },
                complete: function() {
                    $('.btnsimpan').prop('disabled', false);
                    $('.btnsimpan').html('Simpan')
                },
                success: function(response) {
                    if (response.sukses) {
                        Swal.fire({
                            title: 'Cetak Faktur',
                            text: response.sukses + ", cetak faktur ?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, Cetak'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                let windowCetak = window.open(response.cetakfaktur,
                                    "Cetak Faktur Barang Keluar",
                                    "width=200,height=400");

                                windowCetak.focus();
                                window.location.reload();
                            } else {
                                window.location.reload();
                            }
                        })
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + '\n' + thrownError);
                }
            });

            return false;
        });
    });
</script>