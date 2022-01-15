let collapseElementList = [].slice.call(document.querySelectorAll('.collapse'));
//RESET MESSAGE ALERT


//window.addEventListener('editor-tinymce::ExecCommand', event => console.log('TINYMCE-PRE_INIT', event.detail));

let resetMsgAlert = document.getElementById("reset-msg-alert");
jQuery(document).ready(function ($) {
//Ajax Spinner
    let ajaxFormSpinner = document.querySelectorAll(".ajax-status-spinner");

    /**===================================================
     ================ BTN FORM LOAD INPUT =================
     ======================================================
     */
    $(document).on('click', '.btn-form-element', function () {
        $.post(bs_form_ajax_obj.ajax_url, {
            '_ajax_nonce': bs_form_ajax_obj.nonce,
            'action': 'BsFormularHandle',
            method: 'set_form_input',
            type: $(this).attr('data-type')
        }, function (data) {
            if (data.status) {
                let inputText = $('#InputText');
                inputText.val(inputText.val() + data.record);
            } else {
                warning_message(data.msg);
            }
        });
    });

    /**===================================
     ========== BTN SMTP CHECK  ==========
     =====================================
     */
    $(document).on('click', '#load-smtp-check', function () {
        $.post(bs_form_ajax_obj.ajax_url, {
            '_ajax_nonce': bs_form_ajax_obj.nonce,
            'action': 'BsFormularHandle',
            method: 'smtp_check',
        }, function (data) {
            if (data.status) {
                success_message(data.msg);
            } else {
                warning_message(data.msg);
            }
        });
    });

    /**=======================================================
     ================ BTN SEND SUBMIT FORMULAR ================
     ==========================================================
     */
    $(document).on('submit', '.send-bs-form-jquery-ajax-formular', function () {
        let form_data = $(this).serializeObject();
        send_jquery_form_data(form_data);
        return false;
    });

    function send_jquery_form_data(data) {
        $.post(bs_form_ajax_obj.ajax_url, {
            '_ajax_nonce': bs_form_ajax_obj.nonce,
            'action': 'BsFormularHandle',
            daten: data
        }, function (data) {
            if (data.spinner) {
                show_ajax_spinner(data);
                return false;
            }
            if (data.status) {
                success_message(data.msg);
                if (data.reset) {
                    $(".send-bs-form-jquery-ajax-formular").trigger("reset");
                }
                if (data.show_form_edit) {
                    $('#formEditBtn').prop('disabled', false).attr('data-id', data.id);
                    $('#formMeldungenBtn').prop('disabled', false).attr('data-id', data.id);
                    $("#formType").val('update');
                    $('#formId').val(data.id);
                    $('.btn-create').toggleClass('d-none');
                }
            } else {
                warning_message(data.msg);
            }
        });
    }

    /**======================================================
     ================ SEND AUTOSAVE FORMULAR ================
     ========================================================
     */
    let SettingsBsFormTimeout;
    $('.send-bs-form-auto-save-ajax-formular').on('input propertychange change', function () {
        $('.ajax-status-spinner').html('<i class="fa fa-spinner fa-spin"></i>&nbsp; Saving...');
        const form_data = $(this).serializeObject();
        clearTimeout(SettingsBsFormTimeout);
        SettingsBsFormTimeout = setTimeout(function () {
            send_jquery_form_data(form_data);
        }, 1000);
    });

    /**======================================
     ========== AJAX SPINNER SHOW  ==========
     ========================================
     */
    function show_ajax_spinner(data) {
        let msg = '';
        if (data.status) {
            msg = '<i class="text-success fa fa-check"></i>&nbsp; Saved! Last: ' + data.msg;
        } else {
            msg = '<i class="text-danger fa fa-exclamation-triangle"></i>&nbsp; ' + data.msg;
        }
        let spinner = Array.prototype.slice.call(ajaxFormSpinner, 0);
        spinner.forEach(function (spinner) {
            spinner.innerHTML = msg;
        });
    }

    /**=======================================================
     ================ SET PLACEHOLDER MESSAGE ================
     =========================================================
     */
    $(document).on('click', ".placeholder", function () {
        //let value = '<span class="remove">&nbsp;</span>' + $(this).attr('data-value');
        let value = $(this).attr('data-value');
        $(this).removeClass('placeholder').addClass('placeholder-disabled');
        tinymce.get("sendMsgContent").selection.setContent(value);
    });

    /**===================================================
     ================ BTN FORM LOAD INPUT ================
     =====================================================
     */
    $(document).on('click', '.btn-form-reset', function () {
        $('#InputText').val('');
    });

    /**================================================
     ========== TOGGLE FORMULAR COLLAPSE BTN  ==========
     ===================================================
     */
    let formularColBtn = document.querySelectorAll("button.btn-formular-collapse");
    if (formularColBtn) {
        let formCollapseEvent = Array.prototype.slice.call(formularColBtn, 0);
        formCollapseEvent.forEach(function (formCollapseEvent) {
            formCollapseEvent.addEventListener("click", function () {
                //Spinner hide
                if (resetMsgAlert) {
                    resetMsgAlert.classList.remove('show');
                }

                if (ajaxFormSpinner) {
                    let spinnerNodes = Array.prototype.slice.call(ajaxFormSpinner, 0);
                    spinnerNodes.forEach(function (spinnerNodes) {
                        spinnerNodes.innerHTML = '';
                    });
                }
                this.blur();
                if (this.classList.contains("active")) return false;
                let siteTitle = document.getElementById("currentSideTitle");
                siteTitle.innerText = this.getAttribute('data-site');
                let btnType = this.getAttribute('data-type');
                switch (btnType) {
                    case 'table':
                        $('#TableFormulare').DataTable().draw();
                        break;
                    case'formular':
                        get_bs_form_select_pages();

                        break;
                    case'posteingang':
                        load_email_table_data();
                        break;
                }
                remove_active_btn();
                this.classList.add('active');
                this.setAttribute('disabled', true);
            });
        });

        function remove_active_btn() {
            for (let i = 0; i < formCollapseEvent.length; i++) {
                formCollapseEvent[i].classList.remove('active');
                formCollapseEvent[i].removeAttribute('disabled');
            }
        }
    }

    $(document).on('click', '.btn-back-to-table', function () {
        $('.btn-formular-collapse').prop('disabled', false).removeClass('active');
        $('#btnDataTable').addClass('active').prop('disabled', true);
        $('#TableFormulare').DataTable().draw();
    });

    /**====================================================
     ================ FORMULAR Löschen BTN ================
     ======================================================
     */
    $(document).on('click', '.btn-delete-form', function () {
        $.post(bs_form_ajax_obj.ajax_url, {
            '_ajax_nonce': bs_form_ajax_obj.nonce,
            'action': 'BsFormularHandle',
            method: $(this).attr('data-method'),
            type: $(this).attr('data-type'),
            id: $(this).attr('data-id')
        }, function (data) {
            if (data.status) {
                switch (data.method) {
                    case 'delete_bs_formular':
                        $('#TableFormulare').DataTable().draw();
                        success_message(data.msg);
                        break;
                    case'delete_email':
                        $('#TablePosts').DataTable().draw();
                        success_message(data.msg);
                        break;
                }
            } else {
                warning_message(data.msg);
            }
        });
    });


    /**==========================================================
     ================ Load REDIRECT PAGES SELECT ================
     ============================================================
     */

    function get_bs_form_select_pages() {

        $.post(bs_form_ajax_obj.ajax_url, {
            '_ajax_nonce': bs_form_ajax_obj.nonce,
            'action': 'BsFormularHandle',
            method: 'get_pages_select',
        }, function (data) {
            if (data.status) {
                render_bs_formular_edit_template(false, data.record);
            }
        });


    }

    /**=======================================================
     ================ Load Formular Meldungen ================
     =========================================================
     */
    $(document).on('click', '#formMeldungenBtn', function () {
        let id = $(this).attr('data-id');
        load_formular_meldungen(id);
    });

    function load_formular_meldungen(id) {
        $.post(bs_form_ajax_obj.ajax_url, {
            '_ajax_nonce': bs_form_ajax_obj.nonce,
            'action': 'BsFormularHandle',
            method: 'get_input_form_msg',
            id: id,
        }, function (data) {
            if (data.status) {
                render_template_form_meldungen(data.record, data.list);
            } else {
                warning_message(data.msg);
            }
        });
    }

    function render_template_form_meldungen(data, list) {
        let l = bs_form_lang.lang.create_edit;
        let html = `
           <div class="border rounded mt-1 mb-3 shadow-sm p-3 bg-custom-gray" style="min-height: 50vh">
           <div class="d-flex align-items-center">
           <h5><i class="font-blue fa fa-wordpress"></i>&nbsp; ${l[1]}</h5>
           <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
           </div>
           <hr>
              <div class="d-md-flex flex-wrap d-block align-items-center">
                  <button data-bs-toggle="collapse" data-bs-target="#collapseCreateFormularSite"
                          class="btn-back-to-formular btn btn-outline-success btn-sm me-1" type="button">
                      <i class="fa fa-reply-all"></i>
                      &nbsp;${l[2]}
                  </button>
                  <button data-id="${data.id}" data-bs-toggle="collapse" data-bs-target="#collapseEmailEditSite" 
                   id="formEditBtn" class="btn btn-blue-outline btn-sm me-1">
                  <i class="fa fa-envelope-o"></i>
                      &nbsp;${l[3]}
                  </button>
                  
                  <button class="custom-btn btn btn-blue btn-sm me-1" disabled>
                  <i class="fa fa-align-justify"></i>
                      &nbsp;${l[4]}
                  </button>
               </div>
          <hr>
              <h6><i class="font-blue fa fa-edit"></i>&nbsp; <b>${l[5]}</b>
                <small class="d-block">${l[6]} ${data.date} ${l[7]} ${data.time}</small>
                <small class="d-block"><b class="font-blue">${l[8]} [bs-formular id="${data.shortcode}"]</b></small>
              </h6>
              <hr>
              <h5>${l[4]}
              <small style="font-size: .95rem" class="d-block fw-lighter">
              ${l[9]}
              </small>
              </h5>
              <hr>
              <form class="send-bs-form-jquery-ajax-formular" action="#" method="post">
                <input type="hidden" name="method" value="update_meldungen">
                <input type="hidden" name="id" value="${data.id}">
            `;
        $.each(list, function (key, val) {
            let id = createRandomCode(8);
            html += `<div class="mb-3">
                 <label for="${id}" class="form-label mb-0">${val.label}</label>
                 <input type="text" name="meldungen_${val.id}" value="${val.msg}" class="form-control" id="${id}" required>
                 </div>`;
        });
        html += `
            <button type="submit" class="btn btn-blue btn-sm my-3"> <i class="fa fa-save"></i>&nbsp; ${l[10]}</button>
            </form>
            </div>
        `;
        $('#collapseEmailMeldungenSite').html(html);
    }


    /**========================================================
     ================ TOGGLE BTN FORM SETTINGS ================
     ==========================================================
     */
    $(document).on('click', '.btn-form-settings', function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active')
            return false;
        }
        $('.btn-form-settings').removeClass('active')
        $(this).addClass('active');
    });

    /**======================================================
     ================ FORMULAR Bearbeiten BTN ================
     =========================================================
     */
    $(document).on('click', '.btn-edit-bs-formular', function () {
        let id = $(this).attr('data-id');
        $('#collapseCreateFormularSite').empty();
        get_bs_edit_formular(id);
    });

    function get_bs_edit_formular(id) {
        $.post(bs_form_ajax_obj.ajax_url, {
            '_ajax_nonce': bs_form_ajax_obj.nonce,
            'action': 'BsFormularHandle',
            method: 'get_edit_formular',
            id: id
        }, function (data) {
            if (data.status) {
                render_bs_formular_edit_template(data, data.redirect_pages);
            } else {
                warning_message(data.msg);
            }
        });
    }

    function render_bs_formular_edit_template(data, select=false) {

        let l = bs_form_lang.lang.create_edit;

        let html = `
           <div class="border rounded mt-1 mb-3 shadow-sm p-3 bg-custom-gray" style="min-height: 50vh">
           <h5><i class="font-blue fa fa-wordpress"></i>&nbsp;${l[11]} ${data ? l[12] : l[13]}</h5>
           <hr>
              <div class="d-md-flex flex-wrap d-block align-items-center">
                   <button data-bs-toggle="collapse" data-bs-target="#collapseFormularOverviewSite" 
                   class="btn-back-to-table btn btn-outline-success btn-sm me-1" type="button">
                   <i class="fa fa-reply-all"></i>
                      &nbsp;${l[14]}
                  </button>
                  <button data-id="${data && data.id ? data.id : ''}" data-bs-toggle="collapse" data-bs-target="#collapseEmailEditSite" 
                   id="formEditBtn" class="btn btn-blue-outline btn-sm me-1" ${data ? '' : 'disabled'}>
                  <i class="fa fa-envelope-o"></i>
                      &nbsp;${l[3]}
                  </button>
                  
                  <button data-id="${data && data.id ? data.id : ''}" data-bs-toggle="collapse" data-bs-target="#collapseEmailMeldungenSite" 
                   id="formMeldungenBtn" class="btn btn-blue-outline btn-sm me-1" ${data ? '' : 'disabled'}>
                  <i class="fa fa-align-justify"></i>
                      &nbsp;${l[4]}
                  </button>

              </div>
              <hr>
              <span class="${data ? '' : 'd-none'}">
              <h6><i class="font-blue fa fa-edit"></i>&nbsp; <b>${l[5]}</b>
                <small class="d-block">${l[6]} ${data && data.date ? data.date : ''} ${l[7]} ${data && data.time ? data.time : ''}</small>
                <small class="d-block"><b class="font-blue">${l[8]} [bs-formular id="${data && data.shortcode ? data.shortcode : ''}"]</b></small>
              </h6>
              <hr>
              </span>
              <form class="send-bs-form-jquery-ajax-formular mb-3" action="#" method="post">
                  <input type="hidden" name="method" value="add_formular"/>
                  <input id="formType" type="hidden" name="type" value="${data ? 'update' : 'insert'}"/>
                  <input id="formId" type="hidden" name="id" value="${data && data.id ? data.id : ''}"/>
                  <div class="d-flex flex-wrap align-items-center">
                  <div class="col-xl-4 col-lg-6 col-12 mb-3">
                          <label for="formularNameInput" class="form-label">${l[15]}
                          <span class="text-danger">*</span>
                      </label>
                      <input type="text" value="${data && data.bezeichnung ? data.bezeichnung : ''}" name="bezeichnung" class="form-control"
                             id="formularNameInput" required>
                     </div>
                     <!--
                     <span class="cursor-pointer ms-auto border p-2 rounded-circle"><i class="text-muted fa fa-rocket ms-auto fa-2x"></i>&nbsp;</span>
                    -->
                   </div>
                  <hr>
                  <div class="form-btn-box  mb-3">
                      <button type="button" data-type="text"
                              class="btn-form-element btn btn-blue-outline btn-sm mb-1">text
                      </button>
                      <button type="button" data-type="password"
                              class="btn-form-element btn btn-blue-outline btn-sm mb-1">${l[16]}
                      </button>
                      <button type="button" data-type="email"
                              class="btn-form-element btn btn-blue-outline btn-sm mb-1">email
                      </button>
                      <button type="button" data-type="url"
                              class="btn-form-element btn btn-blue-outline btn-sm mb-1">url
                      </button>
                      <button type="button" data-type="number"
                              class="btn-form-element btn btn-blue-outline btn-sm mb-1">number
                      </button>
                      <button type="button" data-type="date"
                              class="btn-form-element btn btn-blue-outline btn-sm mb-1">date
                      </button>
                      <button type="button" data-type="textarea-3"
                              class="btn-form-element btn btn-blue-outline btn-sm mb-1">textarea
                      </button>
                      <button type="button" data-type="select"
                              class="btn-form-element btn btn-blue-outline btn-sm mb-1">select
                      </button>
                      <button type="button" data-type="checkbox"
                              class="btn-form-element btn btn-blue-outline btn-sm mb-1">checkbox
                      </button>
                      <button type="button" data-type="radio-default"
                              class="btn-form-element btn btn-blue-outline btn-sm mb-1">radio
                      </button>
                      <button type="button" data-type="radio-inline"
                              class="btn-form-element btn btn-blue-outline btn-sm mb-1">radio-inline
                      </button>
                       <button type="button" data-type="email-send-select"
                        class="btn-form-element btn btn-blue-outline btn-sm mb-1">E-Mail Select
                      </button>
                      <button type="button" data-type="file"
                        class="btn-form-element btn btn-blue-outline btn-sm mb-1">File Upload
                      </button>
                       <button type="button" data-type="dataprotection"
                              class="btn-form-element btn btn-blue-outline btn-sm mb-1">${l[17]}
                      </button>
                      <button type="button" data-type="button"
                              class="btn-form-element btn btn-blue-outline btn-sm mb-1">button
                      </button>
                   </div>
                  
                  <textarea name="formular" rows="25" cols="50" id="InputText"
                            class="large-text code mb-3" required>${data && data.user_layout ? data.user_layout : ''}</textarea>
                   <hr>
                   <button type="button" data-parent="collapseDivClass" data-bs-toggle="collapse" data-bs-target="#collapseDivClass"  class="btn-form-settings btn btn-blue-outline btn-sm"> ${l[18]}</button>
                   <button type="button" data-parent="collapseButtonSettings" data-bs-toggle="collapse" data-bs-target="#collapseButtonSettings"  class="btn-form-settings btn btn-blue-outline btn-sm"> ${l[19]}</button>
                   <button type="button" data-parent="collapseFormularSettings" data-bs-toggle="collapse" data-bs-target="#collapseFormularSettings"  class="btn-form-settings btn btn-blue-outline btn-sm"> ${l[20]}</button>
                   <div id="settings_parent_wrapper">
                   <div class="collapse" id="collapseDivClass" data-bs-parent="#settings_parent_wrapper">
                   <hr>
                   <h6>${l[21]}
                   <div class="form-text">${l[22]}</div>
                   </h6>
                   <hr>         
                <div class="form-check form-switch">
                <input class="form-check-input" name="class_aktiv" type="checkbox" 
                data-field="inputDivClass" id="checkDivColappse" ${data && data.class_aktiv ? 'checked' : ''}>
                <label class="form-check-label" for="checkDivColappse"> ${l[23]}</label>
                <div class="form-text">${l[24]}</div>
                </div>
               <hr>
                  <div class="col-xl-6 col-lg-8 col-12 my-3">
                     <label for="formDivWrapperInput" class="form-label">${l[25]}
                      </label>
                      <input type="text" value="${data && data.form_class ? data.form_class : ''}" name="form_class" class="form-control" id="formDivWrapperInput">
                      <div class="form-text">${l[26]} (<code>row row-cols-1 row-cols-lg-2 g-lg-2 g-1</code>) </div>
                     </div>
                
                  <div class="col-xl-5 col-lg-6 col-12 my-3">
                        <label for="formularDivInput" class="form-label">${l[27]}
                      </label>
                      <input type="text" value="${data && data.input_class ? data.input_class : ''}" name="input_class" class="form-control" id="formularDivInput">
                        <div class="form-text">${l[28]} <code>col</code> or <code>mb-3</code> </div>
                     </div>
                     
                  <div class="col-xl-5 col-lg-6 col-12 my-3">
                    <label for="formularLabelInput" class="form-label">${l[29]}
                      </label>
                      <input type="text" value="${data && data.label_class ? data.label_class : ''}" name="label_class" placeholder="${l[28]} mb-1"
                      class="form-control" id="formularLabelInput">
                     <div class="form-text">${l[30]} </div>
                     </div>    
                </div><!--css collapse-->
                
                  <div class="collapse" id="collapseButtonSettings" data-bs-parent="#settings_parent_wrapper">
                   <hr>
                   <h6>${l[31]}
                   <div class="form-text">${l[32]}</div>
                   </h6>
                   
                  <div class="col-xl-5 col-lg-6 col-12 my-3">
                    <label for="formularBTNInput" class="form-label">${l[33]}
                      </label>
                      <input type="text" value="${data && data.btn_class ? data.btn_class : ''}" name="button_class" placeholder="${l[28]} btn-outline-secondary btn-sm"
                      class="form-control" id="formularBTNInput">
                     </div>   
                     <button type="button" data-bs-toggle="modal"  data-bs-method="get_fa_icons" data-bs-target="#btnIconModal" class="btnSelectIcon btn-add-slider-icon btn-sm ${data && data.btn_icon ? 'd-none' : ''}"> 
                     ${l[34]}</button> 
                        <div class="d-flex align-items-center mb-4">
                         <button onclick="delete_slider_icon(this);" 
                        type="button" class="btn btn-sm btn-outline-danger remove-slider-icon mt-2 btnSelectIcon ${data && data.btn_icon ? '' : 'd-none'}">
                        <i class="fa fa-trash-o"></i>&nbsp; ${l[35]}
                        </button>
                        <span id="iconContainer" class="slider-icon-wrapper d-inline-block mt-2 ms-2">${data && data.faIcon ? data.faIcon : ''}</span>
                        <input id="iconInput" value="${data && data.btn_icon ? data.btn_icon : ''}" type="hidden" name="btn_icon">
                     </div>
                 </div><!--butoon collapse-->
                 
                 <div class="collapse" data-bs-parent="#settings_parent_wrapper" id="collapseFormularSettings">
                 <hr>
                <h6>${l[36]}</h6>
                
                <div class="form-check form-switch mb-3">
                <input onclick="this.blur()" class="form-check-input" name="redirection_aktiv" 
                type="checkbox" data-bs-toggle="collapse" href="#collapseRedirectSettings" 
                data-field="inputDivClass" id="checkRedirection" ${data && data.redirect_aktiv ? 'checked' : ''}>
                <label class="form-check-label" for="checkRedirection"> ${l[37]}</label>
                <div class="form-text">${l[44]}</div>
                </div>
                    <div class="collapse ${data && data.redirect_aktiv ? 'show' : ''}" id="collapseRedirectSettings">
                        <div class="card p-3">
                        <label for="selectRedirectPage" class="form-label">Redirect Page</label>
                        <select onchange="this.blur()" id="selectRedirectPage" name="redirect_page" class="form-control">
                           <option>${l[39]}</option>`;
                             $.each(select, function (key, val) {
                                 let sel = '';
                                 data.redirect_page == val.id ? sel = 'selected' : sel = '';
                                  html +=`<option value="${val.id}" ${sel}>${val.name}</option>`;
                                });

                        html += `</select>
                        </div>
                        
                        <div class="form-check form-switch my-3">
                        <input onclick="this.blur()" class="form-check-input" name="send_redirection_data_aktiv" 
                        type="checkbox" id="checkRedirectionData" ${data && data.send_redirection_data_aktiv ? 'checked' : ''}>
                        <label class="form-check-label" for="checkRedirectionData"> ${l[40]}</label>
                        <div class="form-text">${l[41]}
                        </div>
                        </div>
                    </div>
                   
                </div><!--collapse-->

                 </div><!--parent-wrapper-->
               <hr>
 
                  <button type="submit" class="btn-create btn btn-blue ms-1 mt-2 btn-sm"><i class="fa fa-save"></i>
                      &nbsp;${data ? l[10] : l[42]}
                  </button>
                  <button type="button" class="btn-create btn-form-reset ms-1 mt-2 btn btn-blue-outline btn-sm ${data ? 'd-none' : ''}"><i
                              class="fa fa-repeat"></i> ${l[43]}
                  </button>
                   <button type="submit" class="btn-create btn btn-blue ms-1 mt-2 btn-sm d-none"><i class="fa fa-save"></i>
                   &nbsp;${l[10]}
                  </button>
              </form>
          </div>
       `;
        let formBox = $('#collapseCreateFormularSite');
        if (data) {
            $('.btn-formular-collapse').prop('disabled', false).removeClass('active');
            $('#formEditCollapseBtn').addClass('active').prop('disabled', true);
        }
        formBox.html(html);
    }

    $(document).on('click', '#checkDivColappse', function () {
        $(this).trigger('blur');
        let field = $(this).attr('data-field');
        $('#' + field).attr('disabled', function (_, attr) {
            return !attr
        });
    });

    /**=============================================================
     ================ BTN LOAD SEND MESSAGE Settings ================
     ================================================================
     */
    $(document).on('click', '#formEditBtn', function () {
        let id = $(this).attr('data-id');
        get_formular_message(id);
    });

    function get_formular_message(id) {
        $.post(bs_form_ajax_obj.ajax_url, {
            '_ajax_nonce': bs_form_ajax_obj.nonce,
            'action': 'BsFormularHandle',
            'id': id,
            method: 'get_formular_message',
        }, function (data) {
            if (data.status) {
                $(".send-bs-form-jquery-ajax-formular").trigger("reset");
                tinymce.remove();
                render_message_template(data);
            } else {
                warning_message(data.msg);
            }
        });
    }

    /**================================================
     ================ MESSAGE TEMPLATE ================
     ==================================================
     */
    function render_message_template(data) {
        let msg = data.message;
        let l = bs_form_lang.lang.create_edit;
        let html = `
       <div class="border rounded mt-1 mb-3 shadow-sm p-3 bg-custom-gray">
    <div class="d-flex align-items-center">
        <h5 class="card-title">
            <i class="font-blue fa fa-wordpress"></i>&nbsp; ${l[45]}
        </h5>
    </div>
    <hr>
   
     <div class="d-md-flex flex-wrap d-block align-items-center">
    <button data-bs-toggle="collapse" data-bs-target="#collapseCreateFormularSite"
            class="btn-back-to-formular btn btn-outline-success btn-sm me-1" type="button">
        <i class="fa fa-reply-all"></i>
        &nbsp;${l[1]}
    </button>
     
     <button class="custom-btn btn btn-blue btn-sm me-1" disabled>
     <i class="fa fa-envelope-o"></i>
         &nbsp;${l[3]}
     </button>
     
     <button id="formMeldungenBtn" data-bs-toggle="collapse" data-bs-target="#collapseEmailMeldungenSite" 
     data-id="${data.id}" class="btn btn-blue-outline btn-sm me-1">
     <i class="fa fa-align-justify"></i>
         &nbsp;${l[4]}
     </button>
    </div>
    <hr>
      <h6><i class="font-blue fa fa-edit"></i>&nbsp; <b>${l[5]}</b>
        <small class="d-block">${l[6]} ${msg.date} ${l[7]} ${msg.time}</small>
        <small class="d-block"><b class="font-blue">Shortcode: [bs-formular id="${msg.shortcode}"]</b></small>
        </h6>
       <hr>
    <form class="send-bs-form-jquery-ajax-formular mb-3" action="#" method="post">
        <input type="hidden" name="method" value="update_form_message"/>
        <input id="formMsgId" type="hidden" name="id" value="${msg.id}">
         
         <div class="col-md-6 mb-3">
            <label class="form-label" for="InputTemplateSelect">${l[62]}</label>
            <select onchange="this.blur()" name="email_template" value=""
                   class="form-control" id="InputTemplateSelect"> `;
        let sel = '';
        for (const [key, val] of Object.entries(data.select)) {
            val.id == data.select_id ? sel = ' selected' : sel = '';
            html += `<option value="${val.id}" ${sel}>${val.bezeichnung}</option>`;
        }
        html += `
            </select>       
        </div>
        
        <div class="col-md-6 mb-3">
            <label class="form-label" for="InputEmailTo">${l[46]} *</label>
            <input type="email" name="sendTo" value="${msg.email_at}"
                   class="form-control" id="InputEmailTo"
                   aria-describedby="InputEmailTo" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label" for="InputEmailCC">Cc..</label>
            <input type="text" name="sendCC"
            value="${msg.email_cc ? msg.email_cc : ''}"
                   class="form-control" id="InputEmailCC"
                   aria-describedby="InputEmailCC">
            <small id="emailHelp" class="form-text text-muted">
                ${l[47]}</small>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label" for="InputBetreff">${l[48]}
                *</label>
            <input type="text" name="betreff" value="${msg.betreff}"
                   class="form-control" id="InputBetreff"
                   aria-describedby="InputBetreff" required>
        </div>
        <hr>
        <h6 style="font-size: 1.1rem" class="mb-0">${l[49]}</h6>
        <span class="d-inline-block pb-2" id="placeholderInputs">`;
        $.each(data.values, function (key, val) {
            html += `<span data-value="${val.values}" data-type="message" class="placeholder">${val.values}  </span> | `;
        });
        html += `</span>
        <hr class="my-1 mx-0">
        <label class="form-label d-block" for="sendMsgContent"><b class="fs-6 strong-font-weight">${l[50]}: *</b></label>
        <textarea id="sendMsgContent" name="message_content"
                  class="formulare-tinymce" required>${msg.message} </textarea>
        <button class="btn btn-blue btn-sm mt-4"><i class="fa fa-save"></i>&nbsp; ${l[51]}
        </button>
    </form>
    <hr>
   
    <h6 style="font-size: 1.1rem" class="mb-0"><i class="font-blue fa fa-caret-down"></i>&nbsp;
        ${l[52]}</h6>
    <hr>
        <button data-bs-toggle="collapse" data-bs-target="#collapseAutoMsg" class="btn btn-blue-outline btn-sm me-3">
        <i class="fa fa-toggle-on"></i>&nbsp; ${l[53]}
        </button>
        <span class="text-${msg.response_aktiv ? 'success' : 'danger'}"> ${l[54]} ${msg.response_aktiv ? [55] : [56]}</span>

     <div class="collapse" id="collapseAutoMsg">
     <hr>
      <form class="send-bs-form-jquery-ajax-formular mb-3" action="#" method="post">
        <input type="hidden" name="method" value="update_auto_message"/>
        <input id="formAutoMsgId" type="hidden" name="id" value="${msg.id}">
        <div class="form-check form-switch">
            <input onclick="this.blur()" class="form-check-input" name="aktiv" 
            type="checkbox" id="checkAutoMessage" ${msg.response_aktiv ? 'checked' : ''}>
            <label class="form-check-label" for="checkAutoMessage">${l[55]}</label>
        </div>
            <hr>
            <div class="col-md-6 mb-3">
                <label class="form-label"
                       for="InputAutoBetreff">${l[48]}</label>
                <input type="text" name="auto_betreff" value="${msg.auto_betreff ? msg.auto_betreff : ''}"
                       class="form-control" id="InputAutoBetreff"
                       aria-describedby="InputAutoBetreff">
            </div>
            <label class="form-label" for="sendMsgAutoContent">${l[50]}: *</label>
            <textarea id="sendMsgAutoContent" name="auto_msg"
                      class="response-formulare-tinymce"> ${msg.auto_msg ? msg.auto_msg : ''}</textarea>
            <button type="submit" class="btn btn-blue-outline btn-sm mt-4"><i
                    class="fa fa-save"></i>&nbsp; ${l[57]}
            </button>
        </div>
    </form>
</div>`;
        $('#collapseEmailEditSite').html(html);
        tinyMceInit();
    }

    function tinyMceInit() {
        tinymce.init({
            selector: "textarea.formulare-tinymce",
            language: 'de',
            height: 400,
            content_css: bs_form.admin_url + '/assets/admin/css/tinyCustom.css',
            valid_elements: '*[*]',
            schema: "html5",
            toolbar_sticky: true,
            toolbar_mode: 'wrap',
            statusbar: true,
            verify_html: false,
            valid_children: "+a[div], +div[*]",
            extended_valid_elements: "div[*]",
            relative_urls: false,
            remove_script_host: false,
            convert_urls: true,
            force_p_newlines: false,
            forced_root_block: false,

            plugins: `print preview importcss searchreplace fullscreen 
            autolink autosave save directionality visualblocks visualchars image link 
            media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime 
            advlist lists wordcount imagetools textpattern noneditable code spellchecker quickbars
            help charmap emoticons `,

            menu: {
                file: {title: 'File', items: 'newdocument restoredraft | preview | print '},
                edit: {title: 'Edit', items: 'undo redo | cut copy paste | selectall | searchreplace'},
                view: {
                    title: 'View',
                    items: 'code | visualaid visualchars visualblocks | spellchecker | preview fullscreen'
                },
                insert: {
                    title: 'Insert',
                    items: 'template link codesample inserttable | charmap emoticons hr | pagebreak nonbreaking anchor toc | insertdatetime'
                },
                format: {
                    title: 'Format',
                    items: 'bold italic underline strikethrough superscript subscript codeformat | formats blockformats fontformats fontsizes align lineheight | forecolor backcolor | removeformat'
                },
                tools: {title: 'Tools', items: ' code wordcount'},
                table: {title: 'Table', items: 'inserttable | cell row column | tableprops deletetable'},
                help: {title: 'Help', items: 'help'}
            },

            toolbar1: `undo redo | formatselect | fontsizeselect |
             bold italic forecolor backcolor | customInsertImage | alignleft aligncenter 
              alignright alignjustify | bullist numlist outdent indent | 
              code | preview | fullscreen`,
            //toolbar2: 'alignleft aligncenter alignright',

            quickbars_selection_toolbar: `bold italic | forecolor backcolor | quicklink | alignleft aligncenter 
             alignright alignjustify | blockformats | h1 h2 h3 h4 h5 h6 `,

        });

        tinymce.init({
            selector: "textarea.response-formulare-tinymce",
            language: 'de',
            height: 300,
            relative_urls: false,
            remove_script_host: false,
            convert_urls: true,
        });
    }


    /**==========================================================
     ================ BTN LOAD POSTEINGANG TABLE ================
     ============================================================
     */
    function load_email_table_data() {
        $.post(bs_form_ajax_obj.ajax_url, {
            '_ajax_nonce': bs_form_ajax_obj.nonce,
            'action': 'BsFormularHandle',
            method: 'get_table_email_data',
        }, function (data) {
            email_table_template(data)
        });
    }

    function email_table_template(data) {
        let l = bs_form_lang.lang.create_edit;
        let html = `
               <div class="border rounded mt-1 mb-3 shadow-sm p-3 bg-custom-gray" style="min-height: 53vh">
                 <h5 class="card-title">
                     <i class="font-blue fa fa-wordpress"></i>&nbsp;${l[63]}
                 </h5>
                 <hr>
                <div id="post-table" class="table-responsive container-fluid pb-5 pt-4">
                     <table id="TablePosts" class="table table-striped nowrap w-100">
                         <thead>
                         <tr>
                             <th>${l[58]}</th>
                             <th>${l[11]}</th>
                             <th>${l[48]}</th>
                             <th>${l[8]}</th>
                             <th>${l[59]}</th>
                             <th>${l[60]}</th>
                             <th></th>
                             <th></th>
                         </tr>
                         </thead>
                         <tfoot>
                         <tr>
                             <th>${l[58]}</th>
                             <th>${l[11]}</th>
                             <th>${l[48]}</th>
                             <th>${l[8]}</th>
                             <th>${l[59]}</th>
                             <th>${l[60]}</th>
                             <th></th>
                             <th></th>
                         </tr>
                         </tfoot>
                     </table>
                 </div>
                 <button data-bs-method="delete_email" data-bs-type="all" data-bs-id="" data-bs-toggle="modal" data-bs-target="#formDeleteModal" class="btn btn-outline-danger btn-sm">
                 <i class="fa fa-trash-o"></i>
                 &nbsp;${l[61]}
                </button>
             </div>`;

        $('#formPostEingangCollapse').html(html);

        $('#TablePosts').DataTable({
            "language": {
                "url": bs_form.data_table
            },

            "columns": [
                null,
                null,
                null,
                null,
                null,
                null,

                {
                    "width": "5%"
                },
                {
                    "width": "5%"
                }
            ],
            columnDefs: [{
                orderable: false,
                targets: [4, 5, 6, 7]
            },
                {
                    targets: [],
                    className: 'text-center'
                },
                {
                    targets: ['_all'],
                    className: 'align-middle'
                }
            ],
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                url: bs_form_ajax_obj.ajax_url,
                type: 'POST',
                data: {
                    action: 'BsFormularHandle',
                    '_ajax_nonce': bs_form_ajax_obj.nonce,
                    method: 'formular_post_table'
                }
            }
        });
    }

    /**=============================================
     ================ FORM Serialize ================
     ================================================
     */
    $.fn.serializeObject = function () {
        let o = {};
        let a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };


    let deleteModal = document.getElementById('formDeleteModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            let button = event.relatedTarget
            let formType = '';
            let id = button.getAttribute('data-bs-id');
            let method = button.getAttribute('data-bs-method');
            let type = button.getAttribute('data-bs-type');
            let modalTitle = deleteModal.querySelector('.modal-title');
            let modalBodyMsg = deleteModal.querySelector('.modal-body');
            switch (method) {
                case 'delete_bs_formular':
                    formType = 'Formular';
                    break;
                case'delete_email':
                    formType = 'E-Mail';
                    document.querySelector('.btn-delete-form').setAttribute('data-type', type);
                    break;
            }
            document.querySelector('.btn-delete-form').setAttribute('data-id', id);
            document.querySelector('.btn-delete-form').setAttribute('data-method', method);
            modalBodyMsg.innerHTML = `<h6 class="text-center"><b class="text-danger">${formType} wirklich löschen?</b><small class="d-block">Diese Aktion kann <b class="text-danger">nicht</b> rückgängig gemacht werden!</small></h6>`;
            modalTitle.innerHTML = `<i class="fa fa-trash-o"></i>&nbsp; ${formType} löschen`;
        });
    }


    function createRandomCode(length) {
        let randomCodes = '';
        let characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let charactersLength = characters.length;
        for (let i = 0; i < length; i++) {
            randomCodes += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return randomCodes;
    }
});


let bsFormIconModal = document.getElementById('btnIconModal');
if (bsFormIconModal) {
    bsFormIconModal.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id');
        let formMethod = button.getAttribute('data-bs-method');
        let dataType = button.getAttribute('data-bs-type');
        let xhr = new XMLHttpRequest();
        let formData = new FormData();
        xhr.open('POST', bs_form_ajax_obj.ajax_url, true);
        formData.append('_ajax_nonce', bs_form_ajax_obj.nonce);
        formData.append('action', 'BsFormularHandle');
        formData.append('method', formMethod);
        formData.append('id', id);
        formData.append('type', dataType);

        xhr.send(formData);

        //Response
        xhr.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                let data = JSON.parse(this.responseText);
                if (data.status) {
                    let iconGrid = document.getElementById('icon-grid');
                    let emailBox = document.getElementById('email-template');
                    switch (data.method) {
                        case'get_fa_icons':
                            emailBox.innerHTML = '';
                            let icons = data.record;
                            let html = '<div class="icon-wrapper">';
                            icons.forEach(function (icons) {
                                html += `<div onclick="set_select_slide_icon(this, '${icons.code}', '${icons.icon}');"
                              data-bs-dismiss="modal"  class="info-icon-item" title="${icons.code} | ${icons.title}">`;
                                html += `<i  class="${icons.icon}"></i><small class="sm-icon">${icons.icon}</small>`;
                                html += '</div>';
                            });
                            html += '</div>';
                            iconGrid.innerHTML = html;
                            break;
                        case'get_email_template':
                            iconGrid.innerHTML = '';
                            emailBox.innerHTML = data.message;
                            break;
                    }
                }
            }
        }
    });
}

function set_select_slide_icon(e, iconCode, icon) {
    let iconContainer = document.getElementById('iconContainer');
    let iconInput = document.getElementById('iconInput');
    iconInput.value = icon + '#' + iconCode;
    iconContainer.innerHTML = `<i  class="${icon}"></i>`;
    let iconButton = document.querySelectorAll('.btnSelectIcon');
    let formNodes = Array.prototype.slice.call(iconButton, 0);
    formNodes.forEach(function (formNodes) {
        formNodes.classList.toggle('d-none');
    });
}

function delete_slider_icon(e) {
    let iconContainer = document.getElementById('iconContainer');
    document.getElementById('iconInput').value = '';
    iconContainer.innerHTML = '';
    let iconButton = document.querySelectorAll('.btnSelectIcon');
    let formNodes = Array.prototype.slice.call(iconButton, 0);
    formNodes.forEach(function (formNodes) {
        formNodes.classList.toggle('d-none');
    });
}



/**=========================================
 ========== AJAX RESPONSE MESSAGE ===========
 ============================================
 */
function success_message(msg) {
    let x = document.getElementById("snackbar-success");
    x.innerHTML = msg;
    x.className = "show";
    setTimeout(function () {
        x.className = x.className.replace("show", "");
    }, 3000);
}

function warning_message(msg) {
    let x = document.getElementById("snackbar-warning");
    x.innerHTML = msg;
    x.className = "show";
    setTimeout(function () {
        x.className = x.className.replace("show", "");
    }, 3000);
}