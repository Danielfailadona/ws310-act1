# WS310-ACT1 Project Guide

## Overview
WS310-ACT1 is a comprehensive SSS (Social Security System) online form application that allows users to fill out and submit personal information forms. The project includes both a form interface for data entry and a CRUD interface for managing applicant records.

## Project Structure
```
ws310-act1/
├── ws310-act1-syntax-guide.md    # Original syntax guide
├── ws310-act1-guide.md          # This guide
├── test.md                      # Test file
├── page1/                       # SSS form interface
│   ├── page1.html              # Main form interface
│   ├── page1.css               # Styling for the form
│   └── page1.php               # Form processing backend
├── crud/                        # CRUD management interface
│   ├── crud.html               # Main CRUD interface
│   ├── crud.css                # Styling for CRUD interface
│   ├── crud-operations/        # Backend operation files
│   │   ├── db_connection.php   # Database connection
│   │   ├── create.php          # Create records
│   │   ├── read.php            # Read all records
│   │   ├── read_single.php     # Read single record
│   │   ├── update.php          # Update records
│   │   └── delete.php          # Delete records
│   └── crud.html               # CRUD interface
├── database/                    # Database schema
│   └── schema.sql              # Database table definitions
├── experimental/                # Experimental features
│   └── popup.php               # Popup modal implementation
└── crud_operations/            # Legacy CRUD operations (moved to crud/crud-operations/)
```

## Key Components

### 1. SSS Form Interface (page1/)
- **page1.html**: Main form with extensive personal data collection
  - Personal information (name, date of birth, sex, civil status)
  - Contact information (phone, email, address)
  - Family information (parents, spouse, children)
  - Employment details (self-employed, OFW, non-working spouse)
  - Dynamic child addition functionality
  - Address synchronization with place of birth
  - Edit mode for updating existing records

- **page1.php**: Backend processing for form submissions
  - Handles both insert and update operations
  - Comprehensive validation for required fields
  - Normalized database insertion across multiple tables
  - Transaction handling for data consistency

### 2. CRUD Management Interface (crud/)
- **crud.html**: Administrative interface for managing applicant records
  - Table view showing all applicants
  - Modal-based add/edit functionality
  - Delete operations with confirmation
  - CSV export capability
  - Integration with CRUD operation files

- **crud-operations/**: Backend files for CRUD operations
  - **db_connection.php**: Shared database connection function
  - **create.php**: Handle record creation
  - **read.php**: Retrieve all applicant records
  - **read_single.php**: Retrieve single applicant with all related data
  - **update.php**: Update applicant records
  - **delete.php**: Delete applicant records

### 3. Database Schema (database/schema.sql)
The application uses a normalized database structure with 6 related tables:

- **applicants**: Main personal information
- **applicant_addresses**: Address details with synchronization option
- **applicant_parents**: Parent information (father/mother)
- **applicant_spouse**: Spouse details
- **applicant_children**: Dynamic children/dependents
- **applicant_employment**: Employment information with different categories

## Key Features

### Form Functionality
- **Dynamic Child Addition**: Users can add multiple children with individual details
- **Civil Status Handling**: Special handling for "Others" civil status with additional input
- **Address Synchronization**: Option to make home address same as place of birth
- **Auto-uppercase**: Text inputs automatically convert to uppercase
- **Edit Mode**: Full edit functionality with data pre-population
- **Validation**: Both client-side (HTML5) and server-side validation

### CRUD Operations
- **Create**: Add new applicant records
- **Read**: View all records in table format
- **Update**: Modify existing records
- **Delete**: Remove records with confirmation
- **Export**: Download data as CSV

### Security Features
- Prepared statements to prevent SQL injection
- Input validation and sanitization
- Transaction handling for data consistency

## Technical Stack
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Backend**: PHP 7.x+
- **Database**: MySQL
- **Architecture**: MVC-like separation of concerns

## Setup Instructions
1. Create the database using `database/schema.sql`
2. Update database credentials in:
   - `page1/page1.php`
   - `crud/crud-operations/db_connection.php`
3. Ensure web server has PHP and MySQL support
4. Place files in web-accessible directory

## API Endpoints
The application uses several AJAX endpoints:
- `crud/crud-operations/read.php` - Get all applicants
- `crud/crud-operations/read_single.php` - Get single applicant with related data
- `crud/crud-operations/create.php` - Create new applicant
- `crud/crud-operations/update.php` - Update applicant
- `crud/crud-operations/delete.php` - Delete applicant
- `page1/page1.php?action=update` - Update from form

## Special Notes
- The application stores all text in uppercase automatically
- Date fields are validated to ensure past dates only
- Phone numbers must follow Philippine format (09XXXXXXXXX or +639XXXXXXXXX)
- Email validation is performed on both client and server side
- The application uses transactions to ensure data consistency across related tables