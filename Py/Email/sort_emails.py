import imaplib
import re
import email

# Function to extract alphanumeric keys from email subjects
def extract_keys(subjects):
    keys = [re.search(r'[A-Z]+\d{7}', subject).group() for subject in subjects if re.search(r'[A-Z]+\d{7}', subject)]
    keys.sort()
    return keys

# Outlook IMAP settings
imap_host = 'outlook.office365.com'  # Outlook IMAP server address
username = 'gursimran_bassi@outlook.com'  # Your Outlook email address
password = 'Bassihome1906'  # Your Outlook email password

# Connect to the Outlook IMAP server
mail = imaplib.IMAP4_SSL(imap_host)
mail.login(username, password)

# Select the inbox
mail.select('inbox')


typ, data = mail.search(None, 'ALL')
if typ == 'OK':
    email_ids = data[0].split()
    
    email_data = {}
    for num in email_ids:
        typ, data = mail.fetch(num, '(BODY[HEADER.FIELDS (SUBJECT)])')
        if typ == 'OK':
            raw_email = data[0][1]
            
            msg = email.message_from_bytes(raw_email)
            subject = msg['subject']
            
            typ, data = mail.fetch(num, '(RFC822)')
            if typ == 'OK':
                raw_email = data[0][1]
                # print(raw_email)
                email_message = email.message_from_bytes(raw_email)
                body = email_message.get_payload()
                
                if subject:
                    keys = extract_keys([subject])
                    if keys:
                        email_data[keys[0]] = body

    # Display the dictionary with keys and body
    print("Email Data:")
    print(email_data)
    # for key, value in email_data.items():
    #     print(f"Key: {key}, Body: {value}")


mail.close()
mail.logout()
