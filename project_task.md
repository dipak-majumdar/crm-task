🔹 Part 1: Basic CRM Features

You need to build a small system with these main modules:

1. CRUD for Contacts

Create, Read, Update, Delete contacts using a PHP framework (Laravel/CodeIgniter/Symfony).

Database should have a proper structure (like contacts table).

Each contact will have:

Name

Email

Phone

Gender (radio buttons)

Profile Image (upload)

Additional File (like a document upload)

2. Custom Fields

You must allow the admin to add extra fields beyond the standard ones.
Example: the admin wants to add “Birthday”, “Company Name”, “Address”.

So, you’ll build a feature where the admin can:

Add a new field dynamically (e.g. from a “Manage Fields” section)

That field appears automatically on the “Add/Edit Contact” form.

Those extra fields get saved in the database (either in a custom_fields table, or as JSON).

Example Database Setup:

contacts
- id
- name
- email
- phone
- gender
- profile_image
- extra_file

contact_custom_fields
- id
- contact_id
- field_name
- field_value


OR, if you want to store them in JSON:

contacts.custom_fields = {"birthday": "2000-01-01", "company": "FastLink IT"}

3. AJAX CRUD

When you create, update, or delete a contact — it should happen without reloading the page.
So:

Use AJAX for those operations.

Show success/error messages dynamically (like a toast or alert).

4. Search and Filter

Add search filters:

By Name

By Email

By Gender

(If you can, also filter by custom fields — optional bonus.)
Filtering should also happen via AJAX (live search).

🔹 Part 2: Merging Contacts

Now, this is the more advanced part — they want a Merge Contacts feature.

1. Merge Two Contacts

In the contact list, add a “Merge” option (maybe a button beside each contact).

When clicked, user picks two contacts to merge.

2. Select Master Contact

Show a popup/modal asking which one will be the “master” contact.

The master one stays active; the other one becomes merged into it.

3. Merge Logic

When merging:

Keep all the master contact’s existing data.

Add secondary contact’s info if it’s missing from the master.

For emails/phones/custom fields:

If master doesn’t have it, add it.

If both have the same field but different values — choose one rule (like keep master’s or combine both).

💡 No data should be deleted.
The merged (secondary) contact should be marked as “merged” or “inactive” — not deleted.

4. Data Integrity

Show clearly in the UI which fields came from the merge.
For example:

“Company Name (merged from Contact #5)”

“Phone numbers combined: 9876543210, 9999999999”

5. Technical Details

If you used:

Separate tables (like contact_custom_fields): update their contact_id to the master contact.

JSON fields: merge the JSON objects properly.

🔹 They’ll Check For

Good Database Design (especially how you handle custom fields)

Clean Code Structure (Laravel controllers, models, routes, AJAX separation)

Smooth UI/UX (easy to use, responsive)

Merging Logic Correctness (no data loss)

Video Demo showing:

CRUD in action

Custom field creation

Merging process

Database change before/after merge