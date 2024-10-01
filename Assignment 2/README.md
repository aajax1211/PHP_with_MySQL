# HomeLuxe Admin Portal

The **HomeLuxe Admin Portal** is designed to allow administrators to manage the product catalog for luxury, minimalist home decor items. This portal enables the admin to perform CRUD (Create, Read, Update, Delete) operations on a specific category of products (in this case, **Furniture**). All data is securely stored in a MySQL database, with proper input validation and the use of prepared statements for database queries.

## Project Description

The **HomeLuxe Admin Portal** allows administrators to manage the inventory of home decor products specifically focusing on **furniture**. Admins can add, view, update, and delete furniture items in the system. The portal connects to a MySQL database using PHP and performs all operations through self-processing pages. Prepared statements are used to ensure security and prevent SQL injection.

### Key Product for Sale

- **Furniture** (Tables, Chairs, Sofas, etc.)

### CRUD Operations

- **Create**: Add new furniture items to the inventory.
- **Read**: View the details of all available furniture items.
- **Update**: Modify details of existing furniture products.
- **Delete**: Remove furniture products from the inventory.

## Technologies Used

- **Frontend**: HTML5, CSS3, JavaScript (Bootstrap optional)
- **Backend**: PHP
- **Database**: MySQL
- **Input Validation**: PHP (server-side)
- **Security**: Prepared Statements in PHP

## Features

1. **Self-processing Pages**:
   - All operations (insert, update, delete) are processed on the same page.
   - No need to navigate to different files for different actions.
2. **CRUD Functionalities**:
   - Add, view, update, and delete furniture products.
3. **MySQL Database Integration**:
   - Secure MySQL database connection using prepared statements for all CRUD operations.
4. **Navigation**:

   - Seamless navigation between pages for a smooth user experience.

5. **User Information**:
   - Each product entry is tagged with the admin name as `ProductAddedBy`. This field is hardcoded and non-customizable.

## Database Structure

The database is set up to store information about **furniture** items. Here is the table structure for **furniture**:

### Table: `furniture`

| Field            | Data Type      | Description                               |
| ---------------- | -------------- | ----------------------------------------- |
| `FurnitureID`    | INT            | Unique identifier for each furniture item |
| `FurnitureName`  | VARCHAR(100)   | Name of the furniture item                |
| `Description`    | TEXT           | Detailed description of the product       |
| `Quantity`       | INT            | Available stock quantity                  |
| `Price`          | DECIMAL(10, 2) | Price of the furniture product            |
| `ProductAddedBy` | VARCHAR(100)   | Hardcoded with the admin's name           |

### Database Initialization (`dbinit.php`)

- This file connects to the MySQL database and creates the necessary tables.
- The field `ProductAddedBy` is hardcoded to "Your Name" and cannot be modified by the user.
