# Mini Version Context Documentation

## Purpose of the Mini Version

The `mini-ver` folder contains a simplified, minimal implementation of the main SSS Online Form system. This mini version serves as a lightweight demonstration of the core functionality with reduced complexity.

## What is the Mini Version?

The mini-ver is a **scaled-down version** of the full WS310-ACT1 SSS Online Form system that:

- Contains only **2 database tables** instead of 6
- Uses only **2 data columns per table** instead of multiple columns
- Maintains the **core functionality** of the original system
- Demonstrates the **essential relationships** between entities
- Provides a **learning and testing environment** without complexity

## Database Structure

### Tables Included:
1. **applicants** table (2 data columns)
   - `ssnum` - Social Security Number
   - `lname` - Last Name
   - `fname` - First Name

2. **applicant_addresses** table (2 data columns)
   - `address_6` - City
   - `address_7` - Province

## Files Included

| File | Purpose |
|------|---------|
| `schema.sql` | Database schema for the mini version |
| `mini_form.html` | Simplified HTML form interface |
| `mini_process.php` | Backend processing for the form |
| `mini_style.css` | Styling for the mini form |
| `mini_crud.php` | CRUD operations interface |

## Why Create a Mini Version?

### 1. **Educational Purposes**
- Easier to understand the core concepts
- Simplified code for learning database relationships
- Reduced cognitive load for beginners

### 2. **Testing Environment**
- Quick testing of basic functionality
- Debugging without complex dependencies
- Faster development cycles

### 3. **Demonstration**
- Shows the essential features of the full system
- Proves the underlying architecture works
- Serves as a foundation for expansion

### 4. **Prototyping**
- Base for building more complex features
- Validates the approach before full implementation
- Allows experimentation with new ideas

## Key Differences from Full Version

| Aspect | Full Version | Mini Version |
|--------|--------------|---------------|
| Tables | 6 tables | 2 tables |
| Data Columns | 10+ per table | 2 per table |
| Features | Complete | Essential only |
| Complexity | High | Low |
| Learning Curve | Steep | Gentle |

## Use Cases

The mini-ver is ideal for:

- **Students** learning database relationships
- **Developers** prototyping new features
- **Testers** validating core functionality
- **Instructors** demonstrating concepts
- **Anyone** wanting to understand the system basics

## Relationship to Main Project

The mini version maintains the same:
- Database relationship concepts
- Form processing patterns
- CRUD operation principles
- Code structure and organization

But with significantly reduced complexity to focus on fundamentals.