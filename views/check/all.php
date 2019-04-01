<? if (count($results)) : ?>
    <? foreach ($results as $result) {
        $type = $result['type'];
        echo MessageBox::$type($result['message']);
    } ?>
<? else : ?>
    <?= MessageBox::success(_("Ihr Stud.IP sieht gut aus! Es konnten keine Probleme festgestellt werden.")) ?>
<? endif ?>
