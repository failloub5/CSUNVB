<?php
ob_start();
?>
    <table class="table table-bordered  table-striped" style="text-align: center">
        <thead class="thead-dark">
        <th>Date</th>
        <th>État</th>
        <th>Véhicule</th>
        <th>Responsable</th>
        <th>Équipage</th>
        <th>Action</th>
        </thead>
        <?php foreach ($shiftsheets as $shiftsheet) : ?>
            <tr>
                <td><a href='?action=showshift&id=<?= $shiftsheet['id'] ?>'
                       class="btn"><?= date('d.m.Y', strtotime($shiftsheet['date'])) ?>  </a></td>
                <td>
                    <?= $shiftsheet['status'] ?>
                </td>
                <td>
                    Jour : <?= $shiftsheet['novaDay'] ?><br>Nuit : <?= $shiftsheet['novaNight'] ?>
                </td>
                <td>
                    Jour : <?= $shiftsheet['bossDay'] ?><br>Nuit : <?= $shiftsheet['bossNight'] ?>
                </td>
                <td>
                    Jour : <?= $shiftsheet['teammateDay'] ?><br>Nuit : <?= $shiftsheet['teammateNight'] ?>
                </td>
                <td>
                    <!-- TODO (XCL): faire un helper qui donne l'action correspondante à l'état actuel -->
                    <?php if ((
                            ($_SESSION['user']['admin'] == true and getNbshiftsheet('open', $baseID) == 0) ||
                            ($_SESSION['user']['admin'] == true and $shiftsheet['statusslug'] == 'close') ||
                            $shiftsheet['statusslug'] == 'open' ||
                            $shiftsheet['statusslug'] == 'reopen')) : ?>
                        <form>
                            <input type="hidden" name="action" value="altershiftsheetStatus">
                            <input type=hidden name="id" value= <?= $shiftsheet['id'] ?>>
                            <button class="btn btn-primary btn-sm">
                                <?= actionForStatus($shiftsheet['statusslug']) ?>
                            </button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php
$content = ob_get_clean();
echo $content;
?>