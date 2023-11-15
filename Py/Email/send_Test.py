import smtplib
from email.message import EmailMessage
import pandas as pd

import random
import string

def generate_random_alphanumeric(length):
    alphanumeric = string.ascii_letters + string.digits  # includes all letters and digits
    return ''.join(random.choice(alphanumeric) for _ in range(length))


email_address = 'gursimran_bassi@outlook.com'  # Your Outlook email address
email_password = 'Bassihome1906'  # Your Outlook email password


with smtplib.SMTP('smtp.office365.com', 587) as smtp:
    smtp.ehlo()
    smtp.starttls()
    smtp.login(email_address, email_password)

   
    for i in range(1,50):
        
        email_subject = 'Love you '+str(i) 

        msg = EmailMessage()
        msg['From'] = email_address
        msg['To'] = 'kaurmandeep2003@gmail.com'
        msg['Subject'] = email_subject
        text = 'Love you so much!! '  *i
        msg.set_content(text)

        smtp.send_message(msg)
        