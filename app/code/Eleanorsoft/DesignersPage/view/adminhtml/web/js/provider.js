define([
    'Magento_Ui/js/form/provider'
], function (Provider) {
    'use strict';

    return Provider.extend({
        defaults: {
            clientConfig: {
                urls: {
                    save: DESIGNER_SAVE_URL,
                    beforeSave: '${ $.validate_url }'
                }
            }
        }
    });
});