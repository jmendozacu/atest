# Module Eleanorsoft_Contact

Submit contact form via ajax

## Intallation
1. Create directory app/code/Eleanorsoft/Contact
2. Copy repository there
3. Run 
    ```
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
php bin/magento indexer:reindex
php bin/magento cache:clean
```
4. Form fields can be added in `view/frontend/templates/form.html`
5. In template `view/adminhtml/email/submitted_form.html` setup fields from form