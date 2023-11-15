import mysql.connector
import pandas as pd 

data = pd.read_csv('removed_data.csv')



mydb = mysql.connector.connect(
  host="localhost",
  user="root",
  password="",
  database="filemanagement"
)
mycursor = mydb.cursor()
for index, row in data.iterrows():
    clientName = row['ClientName']
    id = row['ID']
    print(id)
    # Assuming a proper join condition, and using proper placeholders
    query = "UPDATE wip_test " \
            "INNER JOIN client_contact_details_test " \
            "ON wip_test.Client2Contact_ID = %s " \
            "SET wip_test.Client2Contact_ID = client_contact_details_test.ID " \
            "WHERE client_contact_details_test.ClientName = %s " \
            

    # Execute the query using proper placeholders (%s)
    mycursor.execute(query, (id, clientName))
mydb.commit()



