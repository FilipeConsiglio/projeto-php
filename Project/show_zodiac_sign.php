
<?php
require_once 'layouts/header.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' ||
    empty($_POST['data_nascimento'])) {
    echo '<div class="container mt-5">';
    echo '<div class="alert alert-warning">';
    echo 'Informe uma data de nascimento.';
    echo '</div>';
    echo '<a href="index.php" class="btn btn-primary">Voltar</a>';
    echo '</div>';
    require_once 'layouts/footer.php';
    exit;
}

$data = $_POST['data_nascimento'];

$dataNascimento = DateTime::createFromFormat('!Y-m-d', $data);
$erros = DateTime::getLastErrors();

if (
    !$dataNascimento ||
    ($erros !== false &&
        ($erros['warning_count'] > 0 || $erros['error_count'] > 0)) ||
    $dataNascimento->format('Y-m-d') !== $data ||
    $dataNascimento > new DateTime('today')
) {
    echo '<div class="container mt-5">';
    echo '<div class="alert alert-danger">';
    echo 'Data inválida. Verifique a data informada.';
    echo '</div>';
    echo '<a href="index.php" class="btn btn-primary">Voltar</a>';
    echo '</div>';
    require_once 'layouts/footer.php';
    exit;
}

$arquivoXML = simplexml_load_file('signos.xml');

if ($arquivoXML === false) {
    die('Não foi possível carregar o arquivo signos.xml.');
}

$diaMes = $dataNascimento->format('md');
$signoEncontrado = null;

foreach ($arquivoXML->signo as $signo) {
    $inicio = DateTime::createFromFormat(
        '!d/m/Y',
        (string)$signo->dataInicio . '/2000'
    );

    $fim = DateTime::createFromFormat(
        '!d/m/Y',
        (string)$signo->dataFim . '/2000'
    );

    if (!$inicio || !$fim) {
        continue;
    }

    $inicioMD = $inicio->format('md');
    $fimMD = $fim->format('md');

    if ($inicioMD <= $fimMD) {
        $dentroDoPeriodo =
            $diaMes >= $inicioMD && $diaMes <= $fimMD;
    } else {
        // Signos cujo período atravessa a virada do ano.
        $dentroDoPeriodo =
            $diaMes >= $inicioMD || $diaMes <= $fimMD;
    }

    if ($dentroDoPeriodo) {
        $signoEncontrado = $signo;
        break;
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h2 class="text-center mb-0">
                        Resultado da Consulta
                    </h2>
                </div>

                <div class="card-body text-center">
                    <?php if ($signoEncontrado): ?>
                        <h3 class="mb-3">
                            Seu signo é
                            <?= htmlspecialchars(
                                (string)$signoEncontrado->signoNome,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </h3>

                        <p>
                            <?= htmlspecialchars(
                                (string)$signoEncontrado->descricao,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </p>
                    <?php else: ?>
                        <p class="text-danger">
                            Não foi possível encontrar seu signo.
                        </p>
                    <?php endif; ?>

                    <a href="index.php" class="btn btn-primary mt-3">
                        Consultar outra data
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>
