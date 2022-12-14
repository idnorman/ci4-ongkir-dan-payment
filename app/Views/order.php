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
                    <div class="form-group mb-2">
                        <label for="price" class="form-label">Harga</label>
                        <input type="number" name="price" class="price form-control form-control-sm" id="price" min=1 placeholder=10000 value="10000">
                    </div>
                    <div class="form-group mb-2">
                        <label for="weight" class="form-label">Berat (dalam gram)</label>
                        <input type="number" name="weight" class="weight form-control form-control-sm" id="weight" min=1 placeholder=1000 value="1000">
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
                            <button class="btn btn-primary btn-sm mb-2 btn-count px-3" id="btn-count">Bayar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
            // $('#btn-count').on("click", () => getCost());
            $('#courier').on("change", () => getCost());
            $('#courier-service').on("change", () => setTotalCost());

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
    </script>
</body>

</html>