<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.hummelt-werbeagentur.de
 * @since      1.0.0
 *
 * @package    Bs_Formular2
 * @subpackage Bs_Formular2/admin/partials
 */

$options = get_option($this->basename . '-get-options');
?>


<div class="wp-bs-starter-wrapper">

    <div class="container">
        <div class="card card-license shadow-sm">
            <h5 class="card-header d-flex align-items-center bg-orange py-4">
                <img src="<?=plugins_url('bs-formular2') ?>/admin/images/icon.svg" alt="BS-Formular2" width="32p" height="32px">
                &nbsp;
                BS-Formular 2</h5>
            <div class="card-body pb-4" style="min-height: 72vh">
                <div class="d-flex align-items-center">
                    <h5 class="card-title"><i
                                class="font-yellow fa fa-arrow-circle-right"></i> <?= __( 'Forms', 'bs-formular2' ) ?>
                        / <span id="currentSideTitle"><?= __( 'Overview', 'bs-formular2' ) ?></span>
                    </h5>
                </div>
                <hr>
                <div class="settings-btn-group d-block d-md-flex flex-wrap">
                    <button data-site="<?= __( 'Overview', 'bs-formular2' ) ?>"
                            data-type="table"
                            id="btnDataTable"
                            type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseFormularOverviewSite"
                            class="btn-formular-collapse btn btn-hupa btn-outline-secondary btn-sm active" disabled>
                        <i class="fa fa-envelope-o"></i>&nbsp;
                        <?= __( 'Forms', 'bs-formular2' ) ?>
                    </button>

                    <button data-site="<?= __( 'Create | Edit', 'bs-formular2' ) ?>"
                            data-type="formular"
                            type="button" id="formEditCollapseBtn"
                            data-bs-toggle="collapse" data-bs-target="#collapseCreateFormularSite"
                            class="btn-formular-collapse btn btn-hupa btn-outline-secondary btn-sm"><i
                                class="fa fa-align-justify"></i>&nbsp;
                        <?= __( 'Create | Edit', 'bs-formular2' ) ?>
                    </button>

                    <button data-site="<?= __( 'Inbox', 'bs-formular2' ) ?>"
                            data-type="posteingang"
                            type="button" id="formPostEingangBtn"
                            data-bs-toggle="collapse" data-bs-target="#formPostEingangCollapse"
                            class="btn-formular-collapse btn btn-hupa btn-outline-secondary btn-sm"><i
                                class="fa fa-envelope-open-o"></i>&nbsp;
                        <?= __( 'Inbox', 'bs-formular2' ) ?>
                    </button>

                    <button data-site="<?= __( 'E-Mail Settings', 'bs-formular2' ) ?>"
                            type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseSMTPSite"
                            class="btn-formular-collapse btn btn-hupa btn-outline-secondary btn-sm"><i
                                class="fa fa-gears"></i>&nbsp;
                        <?= __( 'E-Mail Settings', 'bs-formular2' ) ?>
                    </button>

                    <button data-site="<?= __( 'Examples', 'bs-formular2' ) ?>"
                            type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseHelpSite"
                            class="btn-formular-collapse btn btn-hupa btn-outline-secondary btn-sm ms-auto"><i
                                class="hupa-color fa fa-life-ring"></i>&nbsp;
                        <?= __( 'Help', 'bs-formular2' ) ?>
                    </button>
                </div>

                <hr>
                <div id="formular_display_data">
                    <!--  TODO JOB WARNING licence STARTSEITE -->
                    <div class="collapse show" id="collapseFormularOverviewSite"
                         data-bs-parent="#formular_display_data">
                        <div class="border rounded mt-1 mb-3 shadow-sm p-3 bg-custom-gray" style="min-height: 53vh">
                            <h5 class="card-title">
                                <i class="font-blue fa fa-wordpress"></i>&nbsp;<?= __( 'Inbox', 'bs-formular2' ) ?>
                            </h5>
                            <hr>

                            <div id="formular-table" class="table-responsive container-fluid pb-5 pt-4">
                                <table id="TableFormulare" class="table table-striped nowrap w-100">
                                    <thead>
                                    <tr>
                                        <th><?= __( 'Name', 'bs-formular2' ) ?></th>
                                        <th><?= __( 'Shortcode', 'bs-formular2' ) ?></th>
                                        <th><?= __( 'Created', 'bs-formular2' ) ?></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th><?= __( 'Name', 'bs-formular2' ) ?></th>
                                        <th><?= __( 'Shortcode', 'bs-formular2' ) ?></th>
                                        <th><?= __( 'Created', 'bs-formular2' ) ?></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div><!--overview-->

                    <!--Create Formular -->
                    <!--//TODO JOB WARNING ADD & EDIT FORM-->
                    <div class="collapse" id="collapseCreateFormularSite"
                         data-bs-parent="#formular_display_data">
                    </div><!-- End Create Formular -->

                    <!--//TODO JOB WARNING E-MAil Edit SITE-->
                    <div class="collapse" id="collapseEmailEditSite"
                         data-bs-parent="#formular_display_data">
                    </div><!-- End Create Formular -->

                    <!--//TODO JOB Meldungen SITE-->
                    <div class="collapse" id="collapseEmailMeldungenSite"
                         data-bs-parent="#formular_display_data">
                    </div>

                    <!--//TODO JOB POSTEINGANG SITE-->
                    <div class="collapse" id="formPostEingangCollapse"
                         data-bs-parent="#formular_display_data">
                    </div>

                    <!--//TODO JOB WARNING SMTP SETTINGS-->
                    <div class="collapse" id="collapseSMTPSite"
                         data-bs-parent="#formular_display_data">
                        <div class="border rounded mt-1 mb-3 shadow-sm p-3 bg-custom-gray">
                            <div class="d-flex align-items-center">
                                <h5 class="card-title">
                                    <i class="font-blue fa fa-gears"></i>&nbsp;<?= __( 'SMTP Settings', 'bs-formular2' ) ?>
                                </h5>
                                <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                            </div>
                            <hr>
                            <div class="col-xl-8 offset-xl-2 col-lg-10 offset-lg-1 col-md-12 pb-3">
                                <form class="send-bs-form-auto-save-ajax-formular" action="#" method="post">
                                    <input type="hidden" name="method" value="smtp_settings">
                                    <div class="row">
                                        <div class="col-lg-6 col-12 mb-3">
                                            <label for="emailABSInput" class="form-label">
                                                <?= __( 'Name or company of the sender:', 'bs-formular2' ) ?> </label>
                                            <input type="text" class="form-control"
                                                   value="<?= $options[ 'email_abs_name' ] ?>"
                                                   name="email_abs_name"
                                                   id="emailABSInput">
                                            <div id="helpEmailABSInput" class="form-text">
                                                <?= __( 'If the entry remains empty, the page title is used.', 'bs-formular2' ) ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-12"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-12 mb-3">

                                            <label for="absEmailInput" class="form-label">
                                                <?= __( 'Sender Email:', 'bs-formular2' ) ?> <span
                                                        class="text-danger">*</span></label>
                                            <input type="email" class="form-control"
                                                   value="<?= $options[ 'bs_abs_email' ] ?>"
                                                   name="email_adresse"
                                                   id="absEmailInput">
                                            <div id="helpEbsEmailInput" class="form-text">
                                                <?= __( 'In most cases, the provider e-mail must be entered here.', 'bs-formular2' ) ?>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-12 mb-3">
                                            <label for="smtpHostInput" class="form-label">
                                                <?= __( 'SMTP Host:', 'bs-formular2' ) ?> <span
                                                        class="text-danger">*</span></label>
                                            <input type="text" class="form-control"
                                                   value="<?= $options[ 'bs_form_smtp_host' ] ?>"
                                                   placeholder="mail.gmx.net"
                                                   name="smtp_host" id="smtpHostInput">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6 col-12 mb-3">

                                            <label for="smtpPortInput" class="form-label">
                                                <?= __( 'SMTP Port:', 'bs-formular2' ) ?> <span
                                                        class="text-danger">*</span></label>
                                            <input type="number" class="form-control"
                                                   value="<?= $options[ 'bs_form_smtp_port' ] ?>" placeholder="587"
                                                   name="smtp_port" id="smtpPortInput">

                                        </div>

                                        <div class="col-lg-6 col-12 mb-3">
                                            <label for="smtpSecureInput" class="form-label">
                                                <?= __( 'SMTP Secure:', 'bs-formular2' ) ?> <span
                                                        class="text-danger">*</span></label>
                                            <input type="text" class="form-control"
                                                   value="<?= $options[ 'bs_form_smtp_secure' ] ?>" placeholder="tls"
                                                   name="smtp_secure" id="smtpSecureInput"
                                                   autocomplete="cc-number">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-12 mb-3">

                                            <label for="emailUserInput" class="form-label">
                                                <?= __( 'Username:', 'bs-formular2' ) ?> <span
                                                        class="text-danger">*</span></label>
                                            <input type="text" class="form-control"
                                                   value="<?= $options[ 'bs_form_email_benutzer' ] ?>"
                                                   name="email_benutzer"
                                                   id="emailUserInput" autocomplete="cc-number">

                                        </div>
                                        <div class="col-lg-6 col-12 mb-3">
                                            <label for="emailPWInput" class="form-label">
                                                <?= __( 'Password:', 'bs-formular2' ) ?> <span
                                                        class="text-danger">*</span></label>
                                            <input type="password" class="form-control"
                                                   placeholder="xxxxxxxxxxxxxxxxxxxxxx"
                                                   name="email_passwort"
                                                   id="emailPWInput" autocomplete="cc-number">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-12 mb-3">
                                            <div class="form-check form-switch">
                                                <input onclick="this.blur()" class="form-check-input" type="checkbox"
                                                       name="smtp_auth_check"
                                                       id="smtpAuthChecked" <?= ! $options[ 'bs_form_smtp_auth_check' ] ?: 'checked' ?>>
                                                <label class="form-check-label"
                                                       for="smtpAuthChecked"><?= __( 'SMTP Authentication:', 'bs-formular2' ) ?></label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-12 mb-3"></div>
                                    </div>
                                    <hr>
                                    <div class="form-check form-switch">
                                        <input onclick="this.blur()" name="email_aktiv" class="form-check-input"
                                               type="checkbox"
                                               id="checkMailAktiv" <?= ! $options['email_empfang_aktiv'] ?: 'checked' ?>>
                                        <label class="form-check-label" for="checkMailAktiv"><?= __( 'Save email', 'bs-formular2' ) ?>
                                            <?= __( 'active', 'bs-formular2' ) ?></label>
                                    </div>
                                    <hr>
                                    <h5 class="card-title">
                                        <i class="font-blue fa fa-gears"></i>&nbsp;<?= __( 'File Upload Settings', 'bs-formular2' ) ?>
                                    </h5>
                                    <hr>

                                    <div class="row">
                                        <div class="col-lg-6 col-12 mb-3">
                                            <label for="maxSizeInput" class="form-label">
                                                <?= __( 'Maximum File Size (MB):', 'bs-formular2' ) ?> <span
                                                        class="text-danger">*</span></label>
                                            <input type="number" min="1" max="10" class="form-control"
                                                   value="<?= $options[ 'file_max_size' ] ?: '2' ?>"
                                                   name="file_max_size"
                                                   id="maxSizeInput" autocomplete="cc-number">
                                        </div>

                                        <div class="col-lg-6 col-12 mb-3">
                                            <label for="maxSizeAllInput" class="form-label">
                                                <?= __( 'maximum total upload size (MB:)', 'bs-formular2' ) ?> <span
                                                        class="text-danger">*</span></label>
                                            <input type="number" min="1" max="50" class="form-control"
                                                   value="<?= $options[ 'file_max_all_size' ] ?: '6' ?>"
                                                   name="file_max_all_size"
                                                   id="maxSizeAllInput" autocomplete="cc-number">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6 col-12 mb-3">
                                            <label for="uploadMaxFilesInput" class="form-label">
                                                <?= __( 'Max. Files per e-mail', 'bs-formular2' ) ?> <span
                                                        class="text-danger">*</span></label>
                                            <input type="number" min="1" max="10" class="form-control"
                                                   value="<?= $options[ 'upload_max_files' ] ?: '5' ?>"
                                                   name="upload_max_files"
                                                   id="uploadMaxFilesInput" autocomplete="cc-number">
                                        </div>
                                        <div class="col-lg-6 col-12 mb-3">
                                            <label for="uploadMimeTypesInput" class="form-label">
                                                <?= __( 'File-Upload MimeTypes', 'bs-formular2' ) ?> <span
                                                        class="text-danger">*</span></label>
                                            <input type="text" class="form-control"
                                                   value="<?= $options[ 'upload_mime_types' ] ?: 'pdf' ?>"
                                                   name="mime_type"
                                                   id="uploadMimeTypesInput" autocomplete="cc-number">
                                            <div id="uploadMimeTypesHelp" class="form-text">
                                                <?= __( 'Separate MimeTypes with comma or semicolon.<br> (e.g. pdf, jpg, png)', 'bs-formular2' ) ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-check form-switch">
                                        <input onclick="this.blur()" name="multi_upload" class="form-check-input"
                                               type="checkbox"
                                               id="multiUploadAktiv" <?= ! $options[ 'multi_upload' ] ?: 'checked' ?>>
                                        <label class="form-check-label" for="multiUploadAktiv">
                                            <?= __( 'Multiple uploads active', 'bs-formular2' ) ?>
                                        </label>
                                    </div>
                                    <hr>
                                </form>
                                <button id="load-smtp-check" class="btn btn-blue btn-sm" type="button">
                                    <i class="fa fa-gears"></i>&nbsp;
                                    <?= __( 'SMTP Test', 'bs-formular2' ) ?>

                                </button>
                            </div>
                        </div>
                    </div><!--smtp-->

                    <!--//TODO JOB WARNING HELP SITE-->
                    <div class="collapse" id="collapseHelpSite"
                         data-bs-parent="#formular_display_data">
                        <div class="border rounded mt-1 mb-3 shadow-sm p-3 bg-custom-gray">
                            <div class="d-flex align-items-center">
                                <h5 class="card-title">
                                    <i class="font-blue fa fa-life-ring"></i>&nbsp;<?= __( 'Help', 'bs-formular2' ) ?>
                                </h5>
                            </div>
                            <hr>

                            <div class="my-3 p-3 bg-body rounded shadow-sm help-wrapper">
                                <h6 class="border-bottom pb-2 mb-0">Grundlegender Aufbau</h6>
                                <p class="fs-6">
                                    <strong class="d-block text-gray-dark pb-2">Aufbau</strong>

                                    <span class="d-block lh-sm text-muted fs-6">
                                            [label] <b class="text-danger"> text-Label</b><br>
                                            [type=text]  <b class="text-danger">your-text</b>]
                                        </span>
                                </p>
                                <p>
                                    <b class="text-danger">Text Label</b> ist die Label Bezeichnung für das
                                    Formularfeld.<br>
                                    Der Wert <b class="text-danger">your-text</b> wird zum erstellen der E-Mail
                                    Benachrichtigung verwendet.
                                    <span class="pt-2 d-block small">
                                           * Die eckigen Klammern dürfen nicht entfernt werden.
                                        </span>
                                </p>
                                <hr>
                                <strong class="d-block text-gray-dark pb-2">Beispiel Felder mit Ausgabe</strong>
                                <span class="d-block mt-3">
                                        <pre class="mb-1 pb-0">
[label] Vorname
[type=text]  vorname]

[label] Beschreibung
[type=textarea-<b class="text-danger">3</b>] beschreibung]

[label] Test aktiv
[type=checkbox]  test]

[label] Lieblingsfarbe
[type=radio-default]  Orange<b class="text-danger">*</b>, Gelb, Rot]

[label] Senden
[type=button] submit]
            </pre>
                <span class="d-block small my-0 pt-0">
            * Textarea Rows werden mit einer Zahl im type Feld angegeben. In diesem Beispiel <b
                            class="text-danger">3</b> Rows.
                </span>
             <span class="d-block small my-0 pt-0">
                 * Select, Radio und Checkboxen können mit einem <b class="text-danger">*</b> hinter der Bezeichnung als <i>checked</i> bzw. als <i>selected</i> ausgegeben werden.
               </span>
            </span>
                                <hr>
                                <h5>Ausgabe im Frontend</h5>
                                <div class="col-12 col-xl-6 pt-3">
                                    <!----------->
                                    <div class="mb-3"><label class="form-label mb-1"
                                                             for="a94e76fb3e71">Vorname </label><input type="text"
                                                                                                       class="form-control"
                                                                                                       name="a94e76fb3e71"
                                                                                                       id="a94e76fb3e71">
                                    </div>
                                    <div class="mb-3"><label class="form-label mb-1"
                                                             for="f4c7d12158e3">Beschreibung </label><textarea
                                                name="f4c7d12158e3" class="form-control" id="f4c7d12158e3"
                                                rows="3"></textarea></div>
                                    <div class="mb-3">
                                        <div class="form-check"><input onclick="this.blur()"
                                                                       class="form-check-input" name="d3c00ef717a5"
                                                                       type="checkbox" id="d3c00ef717a5"><label
                                                    class="form-check-label" for="d3c00ef717a5">Test aktiv</label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check form-check-inline"><input onclick="this.blur()"
                                                                                         class="form-check-input"
                                                                                         type="radio"
                                                                                         name="762904ddb30a"
                                                                                         id="642260e2ecac"
                                                                                         value="642260e2ecac"
                                                                                         checked=""><label
                                                    class="form-check-label" for="642260e2ecac">Orange</label></div>
                                        <div class="form-check form-check-inline"><input onclick="this.blur()"
                                                                                         class="form-check-input"
                                                                                         type="radio"
                                                                                         name="762904ddb30a"
                                                                                         id="b9553b19d58d"
                                                                                         value="b9553b19d58d"><label
                                                    class="form-check-label" for="b9553b19d58d">Gelb</label></div>
                                        <div class="form-check form-check-inline"><input onclick="this.blur()"
                                                                                         class="form-check-input"
                                                                                         type="radio"
                                                                                         name="762904ddb30a"
                                                                                         id="fff47dea184e"
                                                                                         value="fff47dea184e"><label
                                                    class="form-check-label" for="fff47dea184e">Rot</label></div>
                                    </div>
                                    <div class="d-block">
                                        <button onclick="this.blur()" id="8d1655cb2113" name="8d1655cb2113"
                                                type="submit" class="btn btn-secondary">Senden
                                        </button>
                                    </div>

                                    <!----------->
                                    <hr>
                                    <h5>Pflichtfelder</h5>
                                    <hr>
                                    <pre class="mb-0 pb-0">
<b class="text-danger">Input Felder</b>
[label] Vorname
[type=text<b class="text-danger">*</b>]  vorname]

<b class="text-danger">Textarea</b>
[label] Beschreibung
[type=textarea-3<b class="text-danger">*</b>] beschreibung]

<b class="text-danger">Checkbox</b>
[label] Test aktiv<b class="text-danger">*</b>
[type=checkbox]  test]

<b class="text-danger">Select Feld</b>
[label] Bitte auswählen
[type=select<b class="text-danger">*</b>]  erste Auswahl, Auswahl-2, Auswahl-3]
 </pre>

                                    Pflichtfelder werden mit einem <b class="text-danger">*</b> gekennzeichnet. Radio Input
                                    Felder können nicht als Pflichtfeld
                                    gekennzeichnet werden, weil in der Regel ein Feld immer aktiv ist.

                                    <hr>
                                    <h5>Datenschutz akzeptieren mit Link</h5>
                                    <hr>

                                    <pre>
[label] Ich akzeptiere die <b class="text-danger"> #</b> Datenschutzerklärung <b class="text-danger">#</b> und so weiter
[type=dataprotection] <span class="text-primary"> https://start.hu-ku.com/theme-update</span>]
                                </pre>
                                    <p>
                                        <span class="d-block small"> Zwischen den Rauten <b class="text-danger">#</b> wird der Linktext eingefügt.</span>
                                        <span class="d-block small">Bei <i>dataprotection</i> wird die URL z.B. zur Datenschutzerklärung eingefügt.</span>
                                    </p>
                                    <h6>Ausgabe des Beispiels</h6>
                                    <hr>
                                    <div class="mb-3">
                                        <div class="form-check dscheck"><input onclick="this.blur()"
                                                                               class="form-check-input"
                                                                               data-id="1532472007ae" name="dscheck"
                                                                               type="checkbox" id="1532472007ae"
                                                                               required=""><label class="form-check-label"
                                                                                                  for="1532472007ae">Ich
                                                akzeptiere die <a href="https://start.hu-ku.com/theme-update/">
                                                    Datenschutzbestimmungen</a> und so weiter<span class="text-danger"> *</span> </label>
                                            <div class="invalid-feedback">Sie müssen die Bedingungen akzeptieren, bevor Sie
                                                Ihre Nachricht senden.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h5>E-Mail Select</h5>
                                <div class="form-text">Mit E-Mail Select wird das gesendete Formular an eine ausgewählte E-Mail Adresse gesendet.</div>
                                <hr>
                                <h6 class="border-bottom pb-2 mb-3">Grundlegender Aufbau</h6>

                                <pre>
[label] email-send-select-Label
[type=email-send-select] <b class="text-danger">#</b>Email-Adresse<b class="text-danger">#</b> your-email-send-select-1, <b class="text-danger">#</b>Email-Adresse<b class="text-danger">#</b> your-email-send-select-2, <b class="text-danger">#</b>Email-Adresse<b class="text-danger">#</b>  your-email-send-select-3]
                                    </pre>
                                <p>
                                    <span class="d-block small"> Zwischen den Rauten <b class="text-danger">#</b> wird die E-Mail Adresse eingefügt. An diese Adresse wird bei Auswahl das Formular gesendet.</span>
                                    <span class="d-block small">Hinter der E-Mail Adresse wird die Bezeichnung für das Options-Feld angegeben. </span>
                                    <span class="d-block small">Alle anderen Optionen wie z.B. Pflichtfeld, sind Identisch mit den anderen Formularfeldern.</span>
                                </p>
                                <h6>Ausgabe des Beispiels</h6>
                                <hr>
                                <div class="mb-3"><label class="form-label mb-1" for="9144742e9e47">E-Mail senden an <span class="text-danger"> *</span></label><select onchange="this.blur()" name="9144742e9e47" class="form-control email-send-select form-select" id="9144742e9e47" required=""><option value="">auswählen…</option><option value="eyJzdGF0dXMiOnRydWUsImlkIjoiOGY5ZDVhYjlkOWY4IiwiZW1haWwiOiJ3aWVja2VyQGh1bW1lbHQuY29tIn0="> Messstellenbetrieb</option><option value="eyJzdGF0dXMiOnRydWUsImlkIjoiOGRmYWU5MmQyNGVlIiwiZW1haWwiOiJpbmZvQGplbnN3aWVja2VyLmRlIn0="> Anschluss Erzeugungsanlagen bis 30k</option><option value="eyJzdGF0dXMiOnRydWUsImlkIjoiYjgyZDllMTA2NWFlIiwiZW1haWwiOiJqLndpZWNrZXJAZ214LmRlIn0="> Anschluss Erzeugungsanlagen ab 30kw</option><option value="eyJzdGF0dXMiOnRydWUsImlkIjoiZmEzNTcxMDg3NjkxIiwiZW1haWwiOiJqZW5zQGhhcnotd2ViLmNvbSJ9"> E-Mobilität</option></select><div class="invalid-feedback">Die ausgewählte E-Mail-Adresse ist ungültig.</div></div>
                                <hr>


                                <h5>Daten Upload</h5>
                                <div class="form-text">Für den Daten-Upload stehen verschiedene Optionen in den E-Mail Settings zur Verfügung.</div>
                                <hr>
                                <p>
                                    <span class="d-block small"> Die Option <i class="text-danger">File-Upload MimeTypes</i> kann individuell für jedes File-Upload Input Feld eingestellt werden.</span>
                                </p>

                                <pre>
[label] File Upload
[type=file*]<b class="text-danger">#</b>pdf,jpg,jpeg,png<b class="text-danger">#</b> your-file]
                                </pre>

                                <span class="d-block small">
                                        Zwischen den Rauten <b class="text-danger">#</b> können verschiedene Endungen von erlaubten Datentypen eingetragen werden.</span>
                                <span class="d-block small pb-3">
                                    Die Datentypen ohne Punkt oder MimeType angeben. Die einzelnen Typen können mit Koma, oder Semikolon getrennt werden.
                                </span>

                                <hr>
                                <h5>Javascript Redirect Daten Array</h5>
                                <div class="form-text">Bei einer Weiterleitung nach dem Absenden des Formulars, können die Formulareingaben auf der ausgewählten Seite mit Javascript ausgegeben werden.</div>
                                <hr>
                                <i class="fa fa-info-circle font-blue"> </i> Die Daten stehen nur nach dem Absenden des Formulars auf der Folgeseite zur Verfügung.
                                <p>
                                    <span class="d-block small pt-3">
                                        Jedes erstellte Formular verfügt über eine eindeutige ID. Die ID wird in der Formularübersicht unter Shortcode angezeigt.<br>
                                        Die ID vom Shortcode <b class="strong-font-weight">" [bs-formular id="07107b9b03cb"] "</b> ist in diesem Beispiel <b class="text-danger">
                                        07107b9b03cb.</b>
                                    </span>
                                </p>
                                <p>
                                    <span class="d-block small">
                                     Alle Werte sind in einem Array gespeichert und können mit Javascript abgerufen werden. Um die Eingabewerte anzuzeigen sind folgende Schritte notwendig.
                                    </span>

                                </p>
                                <pre class="bg-light p-3 overflow-hidden"><code>
document.addEventListener("DOMContentLoaded", function (event) {
     if (<b>bs_form_ajax_obj.bs_form_redirect_data</b><b class="text-danger">['07107b9b03cb']</b>) {
        let <b>testDaten = bs_form_ajax_obj.bs_form_redirect_data</b><b class="text-danger">['07107b9b03cb']</b>;
        console.log(testDaten);
    }
});                   </code> </pre>
                                <p>
                                    Die Ausgabe:
                                <pre  class="bg-light p-3 overflow-hidden">
[
    0: "Firma Example",
    1: "inf@hummelt.com",
    2: "2021-12-09"
]
                                </pre>
                                Die Daten stehen nur <b class="strong-font-weight text-danger">einmal nach der Weiterleitung</b> zur Verfügung.
                                </p>
                            </div>
                        </div>
                    </div><!--parent-->
                </div><!--card-->
                <small class="card-body-bottom me-4" style="right: 5rem">DB: <i
                            class="hupa-color">v<?= BS_FORMULAR2_PLUGIN_DB_VERSION ?></i></small>
                <small class="card-body-bottom" style="right: 1.5rem">Version: <i
                            class="hupa-color">v<?= $this->version ?></i></small>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="formDeleteModal" tabindex="-1" aria-labelledby="formDeleteModal"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-hupa">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal"><i
                                    class="text-danger fa fa-times"></i>&nbsp; Abbrechen
                        </button>
                        <button type="button" data-bs-dismiss="modal"
                                class="btn-delete-form btn btn-danger">
                            <i class="fa fa-trash-o"></i>&nbsp; löschen
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!--Modal-->
        <div class="modal fade" id="btnIconModal" tabindex="-1" aria-labelledby="btnIconModal"
             aria-hidden="true">
            <div class="modal-dialog modal-xl modal-fullscreen-xl-down modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-orange">
                        <h5 class="modal-title d-flex justify-content-center"
                            id="exampleModalLabel">
                            <img class="me-2" src="<?=plugins_url('bs-formular2') ?>/admin/images/icon.svg" width="32p" height="32px">
                            <?= __( 'BS-Formular 2', 'bs-formular2' ); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="icon-grid"></div>
                        <div id="email-template"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal"><i
                                    class="text-danger fa fa-times"></i>&nbsp; Schließen
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div id="snackbar-success"></div>
    <div id="snackbar-warning"></div>