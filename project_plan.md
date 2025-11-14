# CRM Task – Development Plan

## 🌟 Goal
Build a mini CRM system in Laravel with:
- CRUD for contacts
- Custom fields (stored in `contact_custom_fields`)
- AJAX operations
- Filtering & search
- Merge contacts feature (no data loss)

---

## 🦯 1. Project Setup

1. Create Laravel project:
   ```bash
   composer create-project laravel/laravel crm-task
   cd crm-task
   php artisan serve
   ```

2. Create a new MySQL database `crm_task`.

3. Update `.env`:
   ```
   DB_DATABASE=crm_task
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. Test connection:
   ```bash
   php artisan migrate
   ```

---

## 🧱 2. Database Setup

1. Create `Contact` model and migration:
   ```bash
   php artisan make:model Contact -m
   ```

2. Define `contacts` table:
   ```php
   Schema::create('contacts', function (Blueprint $table) {
       $table->id();
       $table->string('name');
       $table->string('email')->nullable();
       $table->BigInteger('phone')->nullable();
       $table->enum('gender', ['Male', 'Female'])->nullable();
       $table->string('profile_image')->nullable();
       $table->string('additional_file')->nullable();
       $table->boolean('is_merged')->default(false);
       $table->Integer('merged_into')->nullable();
       $table->timestamps();
   });
   ```

3. Create `ContactCustomField` model and migration:
   ```bash
   php artisan make:model ContactCustomField -m
   ```

4. Define `contact_custom_fields` table:
   ```php
   Schema::create('contact_custom_fields', function (Blueprint $table) {
       $table->id();
       $table->unsignedBigInteger('contact_id');
       $table->string('field_name');
       $table->text('field_value')->nullable();
       $table->timestamps();

       $table->foreign('contact_id')
             ->references('id')
             ->on('contacts')
             ->onDelete('cascade');
   });
   ```

5. Run all migrations:
   ```bash
   php artisan migrate
   ```

---

## 🤉 3. Models & Relationships

**Contact.php**
```php
class Contact extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'gender',
        'profile_image', 'additional_file', 'is_merged', 'merged_into'
    ];

    public function customFields()
    {
        return $this->hasMany(ContactCustomField::class);
    }
}
```

**ContactCustomField.php**
```php
class ContactCustomField extends Model
{
    protected $fillable = ['contact_id', 'field_name', 'field_value'];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
```

---

## 🧑‍💻 4. CRUD Implementation

1. Create controller:
   ```bash
   php artisan make:controller ContactController --resource
   ```

2. Define routes in `web.php`:
   ```php
   Route::get('/', [ContactController::class, 'index'])->name('contacts.index');
   Route::resource('contacts', ContactController::class);
   Route::post('/contacts/merge', [ContactController::class, 'merge'])->name('contacts.merge');
   ```

3. In `ContactController`, implement:
   - `index()` → list contacts
   - `create()` → show form
   - `store()` → add contact with image & custom fields
   - `edit()` → show edit form
   - `update()` → update contact
   - `destroy()` → delete contact
   - `merge()` → handle contact merging logic

4. Create Blade templates:
   - `resources/views/layout.blade.php`
   - `resources/views/contacts/index.blade.php`
   - `resources/views/contacts/form.blade.php`

5. Add dynamic custom field section in form:
   - “Add Custom Field” button → appends new field inputs.

6. Use AJAX for:
   - Create, Update, Delete  
   - Return JSON responses and refresh data via JavaScript.

---

## 🔍 5. Search & Filtering (AJAX)

- Add search fields: Name, Email, Gender
- On input change, send AJAX request to filter contacts:
  ```php
  $contacts = Contact::where('is_merged', false)
      ->when($request->name, fn($q) => $q->where('name', 'like', "%{$request->name}%"))
      ->when($request->email, fn($q) => $q->where('email', 'like', "%{$request->email}%"))
      ->when($request->gender, fn($q) => $q->where('gender', $request->gender))
      ->get();
  ```
- Update list dynamically.

---

## 🔗 6. Merge Feature

1. Add “Merge” button beside each contact in the list.

2. When clicked:
   - Show modal to select another contact to merge with.
   - Ask which one will be the **master** contact.

3. AJAX call to `contacts.merge` route with:
   ```json
   {
     "master_id": 1,
     "secondary_id": 2
   }
   ```

4. In `merge()`:
   - Move `contact_custom_fields` from secondary → master.
   - Combine duplicate fields by appending values (e.g., “Kolkata / Siliguri”).
   - Add secondary email/phone as extra fields if different.
   - Mark secondary as merged (`is_merged = true`, `merged_into = master_id`).

5. Return success JSON → reload contact list.

---

## 🎨 7. UI Enhancements

- Use **Bootstrap 5** for layout and modals.
- Use **SweetAlert2** or toasts for AJAX responses.
- Show merged contacts as inactive or hidden in the list.
- Display merged field info (if needed).

---

## 🥮 8. Testing & Demo Recording

1. Test all CRUD actions.
2. Add & edit contacts with custom fields.
3. Verify filtering works.
4. Merge two contacts and verify:
   - Custom fields moved or combined.
   - Secondary contact flagged as merged.
   - No data loss in database.
5. Record a demo video:
   - Show contact list, form, merge action, and DB updates.

---

## 🗂️ 9. Project Folder Overview

```
app/
 ├── Models/
 │   ├── Contact.php
 │   └── ContactCustomField.php
 ├── Http/Controllers/
 │   └── ContactController.php

resources/
 ├── views/
 │   ├── layout.blade.php
 │   └── contacts/
 │       ├── index.blade.php
 │       └── form.blade.php

public/
 └── uploads/
```

---

## ✅ 10. Final Checklist

| Feature | Status |
|----------|--------|
| Database Setup | ✅ |
| CRUD (Contacts) | ✅ |
| Custom Fields | ✅ |
| AJAX CRUD | ✅ |
| Search & Filter | ✅ |
| Merge Feature | ✅ |
| File Uploads | ✅ |
| Video Recording | 🎥 |
| Code Cleanup & Comments | 🔧 |

---

**End of Plan**