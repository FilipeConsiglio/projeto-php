
<?php
require_once 'layouts/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h2 class="text-center mb-0">
                        Descubra seu Signo
                    </h2>
                </div>

                <div class="card-body">
                    <p class="text-center">
                        Informe sua data de nascimento
                        para descobrir seu signo.
                    </p>

                    <form action="show_zodiac_sign.php"
                          method="POST">

                        <div class="mb-3">
                            <label for="data_nascimento"
                                   class="form-label">
                                Data de nascimento
                            </label>

                            <input
                                type="date"
                                class="form-control"
                                id="data_nascimento"
                                name="data_nascimento"
                                required
                            >
                        </div>

                        <button type="submit"
                                class="btn btn-primary w-100">
                            Descobrir meu signo
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'layouts/footer.php';
?>
   