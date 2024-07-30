# twilio_whatsapp

# Objective
To allow the use of Twillio's API to broadcast messages to our contacts.

# Overview
- The Twilio Whatsapp Extension is developed to send Whatsapp Broadcast Messages to contacts within a group.

- Users are able to use placeholders such as "{phone},{name},{email}" 


# How to use
After installing and enabling the extension,
1. Write a message and select the contact group.

2. Press "Submit"

3. Only contacts who has messaged our business account, will receive your message. If they have not messaged previously, it will not be sent. This is due to a security and privacy feature on Whatsapp's end.



# Installation
- Download the file 
- Run ```composer install``` to download the dependencies
- Enter your Twilio Account Keys in CRM/TwilioWhatsapp/Form/TwilioBroadcast.php line 71 and 72
- Upload the file into your extensions folder

The extension is licensed under [AGPL-3.0](LICENSE.txt).
