<?= $this->extend('main/layout') ?>

<?= $this->section('judul') ?>
Input Transaksi Barang Keluar
<?= $this->endSection('judul') ?>

<?= $this->section('subjudul') ?>

<button type="button" class="btn btn-sm btn-warning" onclick="location.href=('<?= base_url()?>/barangkeluar/data')">
    <i class="fa fa-backward"></i> Kembali
</button>

<?= $this->endSection('subjudul') ?>


<?= $this->section('isi') ?>
<div class="row">
    <div class="col-lg-3">
        <div class="form-group">
            <label for="">No.Faktur</label>
            <input type="text" name="nofaktur" id="nofaktur" value="<?= $nofaktur ?>" class="form-control" readonly>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="">Tgl.Faktur</label>
            <input type="date" name="tglfaktur" id="tglfaktur" class="form-control" value="<?= date('Y-m-d') ?>">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for="">Metode Pembayaran</label>
            <select name="payment_method" id="payment_method" class="form-control">
                <option value="C">Cash</option>
                <option value="K">Hutang</option>
            </select>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <label for="">Cari Pelanggan</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Nama Pelanggan" name="namapelanggan" id="namapelanggan" readonly>
                <input type="hidden" name="idpelanggan" id="idpelanggan">
                <div class="input-group-append">
                    <!-- <button class="btn btn-outline-primary" type="button" id="tombolCariPelanggan" title="Cari pelanggan">
                        <i class="fa fa-search"></i>
                    </button> -->
                    <button class="btn btn-outline-success" type="button" id="tombolTambahPelanggan" title="Tambah Pelanggan">
                        <i class="fa fa-plus-square"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2">
        <div class="form-group">
            <label for="">Kode Barang</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="kodebarang" id="kodebarang">
                <div class="input-group-append">
                    <button class="btn btn-outline-primary" type="button" id="tombolCariBarang">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for="">Nama Barang</label>
            <input type="text" name="namabarang" id="namabarang" class="form-control" readonly>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="">Harga Jual (Rp)</label>
            <input type="text" name="hargajual" id="hargajual" class="form-control" readonly>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="">Qty</label>
            <input type="number" name="jml" id="jml" class="form-control" value="1">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for="">Aksi</label>
            <div class="input-group mb-3">
                <button type="button" class="btn btn-success" title="Simpan Item" id="tombolSimpanItem">
                    <i class="fa fa-save"></i>
                </button>&nbsp;
                <button type="button" class="btn btn-info" title="Selesai Transaksi" id="tombolSelesaiTransaksi">
                    Selesai Transaksi
                </button>&nbsp;
                <!-- <button type="button" class="btn btn-primary" title="Payment Midtrans" id="tombolPay">
                    Pay
                </button> -->
            </div>

        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 tampilDataTemp">

    </div>
</div>
<div class="viewmodal" style="display: none;"></div>
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-W0aGBUm-g1UZb1Af">
</script>
<script>
    let csrfToken = '<?= csrf_token() ?>';
    let csrfHash = '<?= csrf_hash() ?>';

    $('#payment_method').change(function() {
        if ($('#payment_method').val() == 'K') {
            $.ajax({
                url: "<?= base_url()?>/pelanggan/modalData",
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('.viewmodal').html(response.data).show();
                        $('#modaldatapelanggan').modal('show');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + '\n' + thrownError);
                }
            });
        } else {
            $('#namapelanggan').val('')
        }
    })

    function kosong() {
        $('#kodebarang').val('');
        $('#hargajual').val('');
        $('#namabarang').val('');
        $('#jml').val('1');
        $('#kodebarang').focus();
    }

    function simpanItem() {
        let nofaktur = $('#nofaktur').val();
        let kodebarang = $('#kodebarang').val();
        let namabarang = $('#namabarang').val();
        let hargajual = $('#hargajual').val();
        let jml = $('#jml').val();

        if (kodebarang.length == 0) {
            Swal.fire('Error', 'Kode barang harus diinputkan', 'error');
            kosong();
        } else {
            $.ajax({
                type: "post",
                url: "<?= base_url()?>/barangkeluar/simpanItem",
                data: {
                    nofaktur: nofaktur,
                    kodebarang: kodebarang,
                    namabarang: namabarang,
                    jml: jml,
                    hargajual: hargajual
                },
                dataType: "json",
                success: function(response) {
                    if (response.error) {
                        Swal.fire('Error', response.error, 'error');
                        kosong();
                    }
                    if (response.sukses) {
                        Swal.fire('berhasil', response.sukses, 'success');
                        tampilDataTemp();
                        kosong();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + '\n' + thrownError);
                }
            });
        }
    }

    function ambilDataBarang() {
        let kodebarang = $('#kodebarang').val();
        if (kodebarang.length == 0) {
            Swal.fire('Error', 'Kode barang harus diinputkan', 'error');
            kosong();
        } else {
            $.ajax({
                type: "post",
                url: "<?= base_url()?>/barangkeluar/ambilDataBarang",
                data: {
                    kodebarang: kodebarang
                },
                dataType: "json",
                success: function(response) {
                    if (response.error) {
                        Swal.fire('Error', response.error, 'error');
                        kosong();
                    }

                    if (response.sukses) {
                        let data = response.sukses;

                        $('#namabarang').val(data.namabarang);
                        $('#hargajual').val(data.hargajual);
                        $('#jml').focus();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + '\n' + thrownError);
                }
            });
        }

    }

    function tampilDataTemp() {
        let faktur = $('#nofaktur').val();
        $.ajax({
            type: "post",
            url: "<?= base_url()?>/barangkeluar/tampilDataTemp",
            data: {
                [csrfToken]: csrfHash,
                nofaktur: faktur
            },
            dataType: "json",
            beforeSend: function() {
                $('.tampilDataTemp').html("<i class='fa fa-spin fa-spinner'></i>");
            },
            success: function(response) {
                if (response.data) {
                    $('.tampilDataTemp').html(response.data);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + '\n' + thrownError);
            }
        });
    }

    function buatNofaktur() {
        let tanggal = $('#tglfaktur').val();

        $.ajax({
            type: "post",
            url: "<?= base_url()?>/barangkeluar/buatNoFaktur",
            data: {
                tanggal: tanggal
            },
            dataType: "json",
            success: function(response) {
                $('#nofaktur').val(response.nofaktur);
                tampilDataTemp();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + '\n' + thrownError);
            }
        });
    }
    $(document).ready(function() {
        tampilDataTemp();
        $('#tglfaktur').change(function(e) {
            buatNofaktur();
        });

        $('#tombolTambahPelanggan').click(function(e) {
            e.preventDefault();
            $.ajax({
                url: "<?= base_url()?>/pelanggan/formtambah",
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('.viewmodal').html(response.data).show();
                        $('#modaltambahpelanggan').modal('show');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + '\n' + thrownError);
                }
            });
        });

        $('#tombolCariPelanggan').click(function(e) {
            e.preventDefault();
            $.ajax({
                url: "<?= base_url()?>/pelanggan/modalData",
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('.viewmodal').html(response.data).show();
                        $('#modaldatapelanggan').modal('show');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + '\n' + thrownError);
                }
            });
        });

        $('#kodebarang').keydown(function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                ambilDataBarang();
            }
        });

        $('#tombolSimpanItem').click(function(e) {
            e.preventDefault();
            simpanItem();
        });

        $('#tombolCariBarang').click(function(e) {
            e.preventDefault();
            $.ajax({
                url: "<?= base_url()?>/barangkeluar/modalCariBarang",
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('.viewmodal').html(response.data).show();
                        $('#modalcaribarang').modal('show');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + '\n' + thrownError);
                }
            });
        });

        $('#tombolSelesaiTransaksi').click(function(e) {
            e.preventDefault();
            let url = '';
            if ($('#payment_method').val() == 'C') {
                url = 'modalPembayaran';
            } else {
                url = 'modalPembayaranHutang';
            }

            $.ajax({
                type: "post",
                // url: "/barangkeluar/modalPembayaran",
                url: "<?= base_url()?>/barangkeluar/" + url,
                data: {
                    nofaktur: $('#nofaktur').val(),
                    tglfaktur: $('#tglfaktur').val(),
                    idpelanggan: $('#idpelanggan').val(),
                    namapelanggan: $('#namapelanggan').val(),
                    totalharga: $('#totalharga').val(),
                    paymentmethod: $('#payment_method').val(),
                },
                dataType: "json",
                success: function(response) {
                    if (response.error) {
                        Swal.fire('Error', response.error, 'error');
                    }
                    if (response.data) {
                        $('.viewmodal').html(response.data).show();
                        $('#modalpembayaran').modal('show');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + '\n' + thrownError);
                }
            });
        });

        $('#tombolPay').click(function(e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                url: "<?= base_url()?>/barangkeluar/payMidtrans",
                data: {
                    nofaktur: $('#nofaktur').val(),
                    tglfaktur: $('#tglfaktur').val(),
                    idpelanggan: $('#idpelanggan').val(),
                    totalharga: $('#totalharga').val(),
                },
                dataType: "json",
                success: function(response) {
                    if (response.error) {
                        Swal.fire('Error', response.error, 'error');
                    } else {
                        snap.pay(response.snapToken, {
                            // Optional
                            onSuccess: function(result) {
                                let dataResult = JSON.stringify(result, null, 2);
                                let dataObj = JSON.parse(dataResult);

                                $.ajax({
                                    type: "post",
                                    url: "<?= base_url()?>/barangkeluar/finishMidtrans",
                                    data: {
                                        nofaktur: response.nofaktur,
                                        tglfaktur: response.tglfaktur,
                                        idpelanggan: response.idpelanggan,
                                        totalharga: response.totalharga,
                                        order_id: dataObj.order_id,
                                        payment_type: dataObj.payment_type,
                                        transaction_status: dataObj
                                            .transaction_status,
                                    },
                                    dataType: "json",
                                    success: function(response) {
                                        if (response.sukses) {
                                            alert(response.sukses);
                                            window.location.reload();
                                        }
                                    }
                                });


                            },
                            // Optional
                            onPending: function(result) {
                                /* You may add your own js here, this is just example */

                                let dataResult = JSON.stringify(result, null, 2);
                                let dataObj = JSON.parse(dataResult);

                                $.ajax({
                                    type: "post",
                                    url: "<?= base_url()?>/barangkeluar/finishMidtrans",
                                    data: {
                                        nofaktur: response.nofaktur,
                                        tglfaktur: response.tglfaktur,
                                        idpelanggan: response.idpelanggan,
                                        totalharga: response.totalharga,
                                        order_id: dataObj.order_id,
                                        payment_type: dataObj.payment_type,
                                        transaction_status: dataObj
                                            .transaction_status,
                                    },
                                    dataType: "json",
                                    success: function(response) {
                                        if (response.sukses) {
                                            alert(response.sukses);
                                            window.location.reload();
                                        }
                                    }
                                });
                            },
                            // Optional
                            onError: function(result) {
                                /* You may add your own js here, this is just example */

                                let dataResult = JSON.stringify(result, null, 2);
                                let dataObj = JSON.parse(dataResult);

                                $.ajax({
                                    type: "post",
                                    url: "<?= base_url()?>/barangkeluar/finishMidtrans",
                                    data: {
                                        nofaktur: response.nofaktur,
                                        tglfaktur: response.tglfaktur,
                                        idpelanggan: response.idpelanggan,
                                        totalharga: response.totalharga,
                                        order_id: dataObj.order_id,
                                        payment_type: dataObj.payment_type,
                                        transaction_status: dataObj
                                            .transaction_status,
                                    },
                                    dataType: "json",
                                    success: function(response) {
                                        if (response.sukses) {
                                            alert(response.sukses);
                                            window.location.reload();
                                        }
                                    }
                                });
                            }
                        });
                    }
                }
            });
        });
    });
</script>
<?= $this->endSection('isi') ?>