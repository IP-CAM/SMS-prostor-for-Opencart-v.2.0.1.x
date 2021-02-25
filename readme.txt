Brief instructions for working with the module:

1. Go to OCMOD Add-on Installer
2. Click the Load button and select the module archive. The module will be downloaded and installed.

Possible mistakes:
- to successfully install the ocmod module, you need FTP access (Opencart requirement),
or module https://www.opencart.com/index.php?route=marketplace/extension/info&extension_id=18892&filter_search=iSenseLabs,
which removes the requirement for an included ftp.
- check if ZIP addon is installed in php settings

3. After successful loading of the module, you need to integrate its files into Opencart. For this
go to the Add-ons Manager and click the Update button. The SMSPROSTOR module button will appear on the left of the main menu.
4. Now you need to give permission for the module to work. Navigate to Users-> User Groups and opposite the group
Administrator (in most cases) click the Edit button. In the Allow viewing and Allow editing lists
find the tool / smsprostor module (it's almost at the very bottom) and check the boxes. Save your settings.
5. Using the button in the main menu of Opencart go to the SMSPROSTOR module.
6. On the Gateway Settings tab, set the login, password (can be obtained from the link https://prostor-sms.ru/?crmplatform=opencart),
and also enter the administrator's phone in the format 79999999999. The SMS will be sent to the administrator's phone
which are intended for the store owner. Save the module settings.
7. If you entered the correct credentials, your current account will be displayed at the top of the module and on the Gateway Settings tab
the list for selecting the sender's name will become available. Select a sender if necessary.
8. The Newsletter tab is used to send SMS to an individual customer or to a group of customers at once.
9. The Notifications tab is used for SMS settings:
- Integration (off / on) - enables or disables sending all SMS
- Buyer's order status - order status, when the transaction goes to which the buyer will receive an SMS
- Send SMS to the buyer - enables and disables sending SMS to the buyer
- SMS to the buyer - SMS text, you can use the buttons that insert templates
- Administrator's order status - the status of the order, when the transaction goes to which the administrator will receive an SMS
- Send SMS to administrator - enables and disables sending SMS to administrator
- SMS to the administrator - SMS text, you can use the buttons that insert templates
