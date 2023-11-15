import pandas as pd
import csv

data = pd.read_csv('./client_contact_details (1).csv')



client_names = {}
rows_to_delete = []

# Identify duplicates and mark for deletion, keeping the first occurrence
for index, row in data.iterrows():
    client_id = row['ID']
    client_name = row['ClientName']
    client_name = str(client_name).lower()
    # Check if the client name is a duplicate
    if client_name in client_names:
        # If it's a duplicate, note the ID to remove and its first appearance ID
        rows_to_delete.append(client_id)
        # if client_name not in rows_to_delete:
        #     rows_to_delete.append(client_names[client_name])
    else:
        # If it's the first appearance of the client name, store the ID
        client_names[client_name] = client_id

# Remove duplicates by keeping the first appearance of each ClientName
cleaned_data = data[~data['ID'].isin(rows_to_delete)]

# Save the removed IDs along with the cleaned data to a new CSV file
removed_data = data[data['ID'].isin(rows_to_delete)]
removed_data.to_csv('removed_data.csv', index=False, quoting=csv.QUOTE_ALL)  # Save removed IDs to a new CSV file
cleaned_data.to_csv('cleaned_data.csv', index=False, quoting=csv.QUOTE_ALL) 

