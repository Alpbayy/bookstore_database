CREATE TABLE "Publisher" (
  "Publisher_ID" int GENERATED AS IDENTITY PRIMARY KEY,
  "Name" varchar,
  "Address" varchar,
  "Website" varchar
);

CREATE TABLE "Category" (
  "Category_ID" int GENERATED AS IDENTITY PRIMARY KEY,
  "Name" varchar,
  "Description" text
);

CREATE TABLE "Book" (
  "ISBN" varchar PRIMARY KEY,
  "Title" varchar,
  "Pub_Year" int,
  "Price" decimal,
  "Stock_Qty" int,
  "Publisher_ID" int,
  "Category_ID" int
);

CREATE TABLE "Author" (
  "Author_ID" int GENERATED AS IDENTITY PRIMARY KEY,
  "First_Name" varchar,
  "Last_Name" varchar,
  "Biography" text
);

CREATE TABLE "Customer" (
  "Customer_ID" int GENERATED AS IDENTITY PRIMARY KEY,
  "First_Name" varchar,
  "Last_Name" varchar,
  "Email" varchar,
  "Phone" varchar,
  "Shipping_Address" varchar
);

CREATE TABLE "Employee" (
  "Employee_ID" int GENERATED AS IDENTITY PRIMARY KEY,
  "First_Name" varchar,
  "Last_Name" varchar,
  "Job_Title" varchar,
  "Hire_Date" date
);

CREATE TABLE "Shipper" (
  "Shipper_ID" int GENERATED AS IDENTITY PRIMARY KEY,
  "Company_Name" varchar,
  "Phone" varchar
);

CREATE TABLE "Order" (
  "Order_ID" int GENERATED AS IDENTITY PRIMARY KEY,
  "Order_Date" datetime,
  "Total_Amount" decimal,
  "Status" varchar,
  "Customer_ID" int,
  "Employee_ID" int,
  "Shipper_ID" int
);

CREATE TABLE "Book_Author" (
  "ISBN" varchar,
  "Author_ID" int,
  PRIMARY KEY ("ISBN", "Author_ID")
);

CREATE TABLE "Order_Details" (
  "Order_ID" int,
  "ISBN" varchar,
  "Quantity" int,
  "Unit_Price" decimal,
  PRIMARY KEY ("Order_ID", "ISBN")
);

ALTER TABLE "Book" ADD FOREIGN KEY ("Publisher_ID") REFERENCES "Publisher" ("Publisher_ID");

ALTER TABLE "Book" ADD FOREIGN KEY ("Category_ID") REFERENCES "Category" ("Category_ID");

ALTER TABLE "Order" ADD FOREIGN KEY ("Customer_ID") REFERENCES "Customer" ("Customer_ID");

ALTER TABLE "Order" ADD FOREIGN KEY ("Employee_ID") REFERENCES "Employee" ("Employee_ID");

ALTER TABLE "Order" ADD FOREIGN KEY ("Shipper_ID") REFERENCES "Shipper" ("Shipper_ID");

ALTER TABLE "Book_Author" ADD FOREIGN KEY ("ISBN") REFERENCES "Book" ("ISBN");

ALTER TABLE "Book_Author" ADD FOREIGN KEY ("Author_ID") REFERENCES "Author" ("Author_ID");

ALTER TABLE "Order_Details" ADD FOREIGN KEY ("Order_ID") REFERENCES "Order" ("Order_ID");

ALTER TABLE "Order_Details" ADD FOREIGN KEY ("ISBN") REFERENCES "Book" ("ISBN");
