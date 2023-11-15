import mysql.connector

mydb = mysql.connector.connect(
  host="localhost",
  user="root",
  password="",
  database="filemanagement"
)
mycursor = mydb.cursor()

mycursor.execute("SELECT * FROM invoices")

myresult = mycursor.fetchall()

for x in myresult:
  print(x)