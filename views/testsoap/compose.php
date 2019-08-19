<form action="<?= PluginEngine::getLink($plugin, array(), "testsoap/soapit") ?>"
      method="post"
      data-dialog
      class="default">

    <? if (Request::get("output")) : ?>
        <textarea readonly
                  style="min-height: 400px;">
            <?= htmlReady(Request::get("output")) ?>
        </textarea>
    <? endif ?>

    <label>
        <?= _("URL der WSDL-Datei") ?>
        <input type="text"
               name="wsdl"
               placeholder="https://..."
               required
               value="<?= htmlReady(Request::get("wsdl")) ?>">
    </label>

    <label>
        <?= _("Name der Methode") ?>
        <input type="text"
               name="method"
               value="<?= htmlReady(Request::get("method")) ?>"
               required>
    </label>

    <label>
        <?= _("Nutzername") ?>
        <input type="text"
               name="username"
               value="<?= htmlReady(Request::get("username")) ?>">
    </label>

    <label>
        <?= _("Passwort") ?>
        <input type="password"
               name="password"
               value="<?= htmlReady(Request::get("password")) ?>">
    </label>

    <label>
        <?= _("Parameter als JSON") ?>
        <textarea name="json" placeholder="[]"><?= htmlReady(Request::get("json")) ?></textarea>
    </label>

    <div data-dialog-button>
        <?= \Studip\Button::create(_("Ausführen")) ?>
    </div>



</form>