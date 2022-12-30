<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengiriman Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body class="container mt-2 d-flex justify-content-center">
    <div class="col-12">
        <h2 class="text-center mb-3">API RajaOngkir dan Midtrans</h2>
        <div class="row">
            <div class="col-7">
                <div class="border border-secondary rounded px-3 py-2 mb-2">
                    <h5>Asal Kota</h5>
                    <div class="form-group mb-2">
                        <label for="select-origin-province" class="form-label">Pilih Provinsi</label>
                        <select class="select-origin-province form-select" name="origin-province" id="select-origin-province">
                            <option selected disabled>Pilih Provinsi</option>
                            <?php foreach ($provinces as $province) : ?>
                                <option value="<?= $province->province_id ?>"><?= $province->province ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label for="select-origin-city" class="form-label">Pilih Kabupaten/Kota</label>
                        <select class="select-origin-city form-select" name="origin-city" id="select-origin-city" disabled=true>
                            <option selected disabled>Pilih Kabupaten/Kota</option>
                        </select>
                    </div>
                </div>
                <div class="border border-secondary rounded px-3 py-2">
                    <h5>Destinasi</h5>
                    <div class="form-group mb-2">
                        <label for="select-destination-province" class="form-label">Pilih Provinsi</label>
                        <select class="select-destination-province form-select" name="destination-province" id="select-destination-province">
                            <option selected disabled>Pilih Provinsi</option>
                            <?php foreach ($provinces as $province) : ?>
                                <option value="<?= $province->province_id ?>"><?= $province->province ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label for="select-destination-city" class="form-label">Pilih Kabupaten/Kota</label>
                        <select class="select-destination-city form-select" name="destination-city" id="select-destination-city" disabled=true>
                            <option selected disabled>Pilih Kabupaten/Kota</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-5">
                <div class="border border-secondary rounded px-3 py-2 mb-2">
                    <h5>Total</h5>
                    <div class="row mb-2">
                        <div class="col-6">
                            <label for="price" class="form-label">Harga</label>
                            <input type="number" name="price" class="price form-control form-control-sm" id="price" min=1 placeholder=10000 value="10000">
                        </div>
                        <div class="col-6">
                            <label for="weight" class="form-label">Berat (dalam gram)</label>
                            <input type="number" name="weight" class="weight form-control form-control-sm" id="weight" min=1 placeholder=1000 value="1000">
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label for="courier" class="form-label">Pilih Kurir</label>
                        <select class="courier form-select form-select-sm mb-2" name="courier" id="courier">
                            <option selected disabled>Pilih Kurir</option>
                            <option value="jne">JNE</option>
                            <option value="pos">POS</option>
                            <option value="tiki">TIKI</option>
                        </select>
                        <label for="courier-service" class="form-label">Pilih Layanan</label>
                        <select class="courier-service form-select form-select-sm" name="courier-service" id="courier-service" disabled="true">
                            <option selected disabled>Pilih Layanan</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-auto">
                            <input type="number" name="total" class="form-control form-control-sm total" id="total" disabled>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary btn-sm mb-2 px-3 btn-snap" id="btn-snap">Snap</button>
                        </div>
                        <hr class="border border-primary opacity-25 mb-2">
                        <h6>CoreAPI</h6>
                        <div class="col-auto">
                            <button class="btn btn-primary btn-sm mb-2 px-3 btn-card-modal" id="btn-card-modal" data-bs-toggle="modal" data-bs-target="#cardModal">Card</button>
                            <button class="btn btn-primary btn-sm mb-2 px-3 btn-gopay-qris-modal" id="btn-gopay-qris-modal">Gopay/QRIS</button>
                            <button class="btn btn-primary btn-sm mb-2 px-3 btn-bca-va-modal" id="btn-bca-va-modal">BCA VA</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <div class="border border-secondary rounded px-3 py-2 mb-2">
                    <pre><div id="result-json">JSON result will appear here after payment:<br></div></pre>
                </div>
            </div>
        </div>
    </div>

    <!-- Card (CoreAPI) Payment Modal -->
    <div class="modal fade" id="cardModal" tabindex="-1" aria-labelledby="cardModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="cardModalLabel">Card (CoreAPI)</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success d-none" id="card-pay-success" role="alert">
                        Pembayaran Berhasil dilakukan
                    </div>
                    <div class="form-group mb-2">
                        <label for="card-number" class="form-label">Card Number</label>
                        <input type="number" name="card-number" class="card-number form-control form-control-sm" id="card-number" min=1 placeholder=4811111111111114 value="4811111111111114">
                    </div>
                    <div class="form-group mb-2">
                        <label for="card-exp-month" class="form-label">EXP (Month)</label>
                        <input type="number" name="card-exp-month" class="card-exp-month form-control form-control-sm" id="card-exp-month" min=1 placeholder=02 value="02">
                    </div>
                    <div class="form-group mb-2">
                        <label for="card-exp-year" class="form-label">EXP (Year)</label>
                        <input type="number" name="card-exp-year" class="card-exp-year form-control form-control-sm" id="card-exp-year" min=1 placeholder=2025 value="2025">
                    </div>
                    <div class="form-group mb-2">
                        <label for="card-cvv" class="form-label">CVV</label>
                        <input type="number" name="card-cvv" class="card-cvv form-control form-control-sm" id="card-cvv" min=1 placeholder=123 value="123">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary btn-card-pay" type="button" id="btn-card-pay">
                        Bayar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Card (CoreAPI) Payment Modal -->

    <!-- GoPay/QRIS (CoreAPI) Payment Modal -->
    <div class="modal fade" id="gopayQrisModal" tabindex="-1" aria-labelledby="gopayQrisModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="gopayQrisModalLabel">GoPay/QRIS (CoreAPI)</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success d-none" id="gopay-qris-pay-success" role="alert">
                        Pembayaran Berhasil dilakukan
                    </div>
                    <div class="col text-center" id="gopay-qris-pay-spinner-container">
                    </div>
                    <div class="col text-center" id="gopay-qris-pay-qr-container">
                        <img src="" class="d-none" id="gopay-qris-pay-qr">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End of GoPay/QRIS (CoreAPI) Payment Modal -->

    <!-- BCA VA (CoreAPI) Payment Modal -->
    <div class="modal fade" id="bcaVaModal" tabindex="-1" aria-labelledby="bcaVaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="bcaVaModalLabel">BCA VA (CoreAPI)</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success d-none" id="bca-va-pay-success" role="alert">
                        Pembayaran Berhasil dilakukan
                    </div>
                    <div class="col text-center" id="bca-va-pay-spinner-container">
                    </div>
                    <div class="col text-center" id="bca-va-pay-number-container">
                        <div class="input-group mb-3 d-none" id="bca-va-pay-number-group">
                            <span class="input-group-text" id="basic-addon1">BCA</span>
                            <input type="text" class="form-control" id="bca-va-pay-number" placeholder="" value="" aria-label="Username" aria-describedby="basic-addon1" disabled>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End of BCA VA (CoreAPI) Payment Modal -->
    <script src=" https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= env('midtrans.clientKey') ?>"></script>
    <script id="midtrans-script" type="text/javascript" src="https://api.midtrans.com/v2/assets/js/midtrans-new-3ds.min.js" data-environment="sandbox" data-client-key="<?= env('midtrans.clientKey') ?>"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#select-origin-province').select2();
            $('#select-origin-city').select2();
            $('#select-destination-province').select2();
            $('#select-destination-city').select2();
            $('#select-origin-province').change(() => {
                setOriginCity($('#select-origin-province').select2().val());
            });
            $('#select-destination-province').change(() => {
                setDestinationCity($('#select-destination-province').select2().val());
            });
            $('#courier').on("change", () => getCost());
            $('#courier-service').on("change", () => setTotalCost());
            $('#btn-snap').on('click', () => snapPayment());
            $('#btn-card-pay').on('click', () => cardPayment());
            $('#btn-gopay-qris-modal').on('click', () => gopayQrisPayment());
            $('#btn-bca-va-modal').on('click', () => bcaVaPayment());
        });

        async function setOriginCity(province) {
            $('#select-origin-city').prop('disabled', true);
            await fetch('<?= base_url() . route_to('getCity') ?>?' + new URLSearchParams({
                    province: province,
                }))
                .then(response => response.json())
                .then(result => {
                    let options = result.map((el) => {
                        return $("<option></option>").val(el.city_id).html(el.city_name);
                    });
                    $('#select-origin-city').find('option').remove()
                        .end().append(options);
                    $('#select-origin-city').prop('disabled', false);
                });

        };

        async function setDestinationCity(province) {
            $('#select-destination-city').prop('disabled', true);
            await $.ajax({
                url: "<?= base_url() . route_to('getCity') ?>",
                type: 'GET',
                data: {
                    province: province
                },
                success: function(result) {
                    let options = JSON.parse(result).map((el) => {
                        return $("<option></option>").val(el.city_id).html(el.city_name);
                    });
                    $('#select-destination-city').find('option').remove()
                        .end().append(options);
                    $('#select-destination-city').prop('disabled', false);
                }
            });
        };

        async function getCost() {
            let origin = $('#select-origin-city').select2().val();
            let destination = $('#select-destination-city').select2().val();
            let weight = $('#weight').val();
            let courier = $('#courier').val();

            $('#courier-service').attr('disabled', true);
            await fetch('<?= base_url() . route_to('getCost') ?>', {
                    method: "POST",
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        origin: origin,
                        destination: destination,
                        weight: weight,
                        courier: courier
                    })
                })
                .then(response => response.json())
                .then(response => {
                    let options = response.costs.map((el) => {
                        return $("<option></option>").data('data-value', el.cost[0].value).attr('data-value', el.cost[0].value).val(el.service).html(`${el.description}(${el.service}) - Rp. ${el.cost[0].value} - ${el.cost[0].etd} hari`);
                    });
                    let disabledOption = $("<option></option>").html('Pilih Layanan').attr('disabled', true).attr('selected', true);
                    $('#courier-service').find('option').remove()
                        .end().append(disabledOption).append(options);
                    $('#courier-service').attr('disabled', false);
                });
        };

        function setTotalCost() {
            let price = $('#price').val();
            let courierCost = $('#courier-service option:selected').data('value');
            $('#total').val(parseInt(price) + parseInt(courierCost));
        }

        async function snapPayment() {
            let origin = $('#select-origin-city').select2().val();
            let destination = $('#select-destination-city').select2().val();
            let total = $('#total').val();
            await axios.post('<?= base_url() . route_to('snapPayment') ?>', {
                    origin: origin,
                    destination: destination,
                    total: total
                })
                .then(function(response) {
                    snap.pay(response.data, {
                        // Optional
                        onSuccess: function(result) {
                            /* You may add your own js here, this is just example */
                            document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                        },
                        // Optional
                        onPending: function(result) {
                            /* You may add your own js here, this is just example */
                            document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                        },
                        // Optional
                        onError: function(result) {
                            /* You may add your own js here, this is just example */
                            document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                        }
                    });
                })
                .catch(function(error) {
                    console.log(error);
                });
        }

        function cardPayment() {
            let spinner = '<span class="spinner-border spinner-border-sm" role="status" id="card-pay-spinner" aria-hidden="true"></span>';

            $("#btn-card-pay").text('Getting Token ').attr('disabled', true).append(spinner);

            let cardData = {
                "card_number": $("#card-number").val(),
                "card_exp_month": $("#card-exp-month").val(),
                "card_exp_year": $("#card-exp-year").val(),
                "card_cvv": $("#card-cvv").val()
            };

            var options = {
                onSuccess: function(response) {
                    var token_id = response.token_id;
                    cardPaymentPost(token_id)
                },
                onFailure: function(response) {
                    console.log('Fail to get card token_id, response:', response);
                }
            };

            MidtransNew3ds.getCardToken(cardData, options);
        }
        async function cardPaymentPost(token_id) {
            let spinner = '<span class="spinner-border spinner-border-sm" role="status" id="card-pay-spinner" aria-hidden="true"></span>';

            let origin = $('#select-origin-city').select2().val();
            let destination = $('#select-destination-city').select2().val();
            let total = $('#total').val();

            $("#btn-card-pay").text('Making Payment ').attr('disabled', true).append(spinner);
            await axios.post('<?= base_url() . route_to('cardPayment') ?>', {
                    "token_id": token_id,
                    "origin": origin,
                    "destination": destination,
                    "total": total
                })
                .then(function(response) {
                    console.log(response);
                    $('#card-pay-success').removeClass('d-none');
                    document.getElementById('result-json').innerHTML += JSON.stringify(response.data, null, 2);
                    setTimeout(function() {
                        $('#cardModal').modal('hide');
                        $('#card-pay-spinner').remove();
                        $("#btn-card-pay").text('Bayar').attr('disabled', false);
                        $('#card-pay-success').addClass('d-none');
                    }, 3000);
                })
                .catch(function(error) {
                    console.log(error);
                });
        }
        async function gopayQrisPayment() {
            $('#gopayQrisModal').modal('show');
            let spinner = '<div class="spinner-grow text-primary" id="gopay-qris-pay-spinner" role="status"></div>';

            let origin = $('#select-origin-city').select2().val();
            let destination = $('#select-destination-city').select2().val();
            let total = $('#total').val();

            $("#gopay-qris-pay-spinner-container").append(spinner);
            await axios.post('<?= base_url() . route_to('gopayQrisPayment') ?>', {
                    "origin": origin,
                    "destination": destination,
                    "total": total
                })
                .then(function(response) {
                    let image = response.data.actions[0].url;
                    // console.log(response.data.actions[0].url);
                    $('#gopay-qris-pay-spinner').remove();
                    $('#gopay-qris-pay-qr').attr('src', image).removeClass('d-none');
                    document.getElementById('result-json').innerHTML += JSON.stringify(response.data, null, 2);
                })
                .catch(function(error) {
                    console.log(error);
                });
        }


        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('8a8025c6f9e9e3742733', {
            cluster: 'ap1'
        });
        pusher.unsubscribe('fixit');
        var channel;


        async function bcaVaPayment() {
            $('#bcaVaModal').modal('show');
            $('#bca-va-pay-number-group').addClass('d-none');
            let spinner = '<div class="spinner-grow text-primary" id="bca-va-pay-spinner" role="status"></div>';

            let origin = $('#select-origin-city').select2().val();
            let destination = $('#select-destination-city').select2().val();
            let total = $('#total').val();

            $("#bca-va-pay-spinner-container").append(spinner);
            await axios.post('<?= base_url() . route_to('bcaVaPayment') ?>', {
                    "origin": origin,
                    "destination": destination,
                    "total": total
                })
                .then(function(response) {
                    // console.log(response);
                    let event = 'bcava-' + response.data.order_id;
                    console.log(event);
                    channel = pusher.subscribe('fixit');
                    channel.bind(event, function(data) {
                        // console.log('event test');
                        // console.log(data);
                        // alert(JSON.stringify(data));
                        $('#bca-va-pay-success').removeClass('d-none');
                    });
                    $('#bca-va-pay-spinner').remove();
                    $('#bca-va-pay-number-group').removeClass('d-none');
                    $('#bca-va-pay-number').val(response.data.va_numbers[0].va_number);
                    document.getElementById('result-json').innerHTML += JSON.stringify(response.data, null, 2);
                })
                .catch(function(error) {
                    console.log(error);
                });
        }
    </script>

</body>

</html>