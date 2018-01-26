define([
    'jquery',
    'uiComponent',
    'mage/validation'
], function ($, Component) {
    'use strict';

    return Component.extend({
        initialize: function (config) {
            self = this;
            this._super();

            this.subjects = this.getSubjects();
            this.observe['subjects'];
            this.prepareFunctions(config);
        },

        beforeSend: function () {
            return true;
        },

        onSendError: function () {
            return true;
        },

        onSendSuccess: function () {
            return true;
        },

        prepareFunctions: function (config) {
            if (config.beforeSend) {
                eval('self.beforeSend = ' + config.beforeSend);
            }

            if (config.onSendError) {
                eval('self.onSendError = ' + config.onSendError);
            }

            if (config.onSendSuccess) {
                eval('self.onSendSuccess = ' + config.onSendSuccess);
            }
        },

        validateContactForm: function (jForm) {
            return jForm.validation() && jForm.validation('isValid');
        },

        submitContactForm: function(form) {
            var jForm = $(form),
                jSubmit = jForm.find(':submit');

            if (self.beforeSend() !== false && this.validateContactForm(jForm)) {
                jSubmit.prop('disabled', true);

                $.post(jForm.attr('action'), this.getFormValues(jForm), function (result) {
                    if (self.onSendSuccess() !== false) {
                        self.showSuccessMessage(result);
                    }
                }).fail(function (error) {
                    if (self.onSendError() !== false) {
                        self.showErrorMessage(error.responseText);
                    }
                }).always(function () {
                    jForm[0].reset();
                    jSubmit.prop('disabled', false);
                });
            }
        },

        getFormValues: function (jForm) {
            var values = {};

            $.each(jForm.serializeArray(), function(i, field) {
                values[field.name] = field.value;
            });

            return values;
        },

        getSubjects: function () {
            return [
                {value: 0, label: 'I need to change my order before it ships!'},
                {value: 1, label: 'I need to change my order before it bills!'},
            ];
        },

        showErrorMessage: function (message) {
            this.showMessage(message, 'error');
        },

        showSuccessMessage: function (message) {
            this.showMessage(message, 'success');
        },

        showMessage: function (message, type) {
            var jMessage = $('.message'),
                cssClass = 'message message-' + type + ' ' + type;

            jMessage.find('div').text(message);
            jMessage.removeClass();
            jMessage.addClass(cssClass);
            $('.page.messages').show();
        }
    });
});