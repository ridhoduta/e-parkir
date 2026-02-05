# 🎬 SOFT DELETE STEP-BY-STEP VIDEO TUTORIAL (Text Version)

## Tutorial 1: Setup Database

### Duration: 2 minutes

```
STEP 1: Open phpMyAdmin
├─ Go to http://localhost/phpmyadmin
├─ Select database: web_parkir
└─ Click "SQL" tab

STEP 2: Run SQL Script
├─ Copy content dari SOFT_DELETE_SETUP.sql
├─ Paste ke SQL editor
├─ Click "Go" button
└─ Success! Columns added

STEP 3: Verify
├─ Go to "users" table
├─ Click "Structure"
├─ Scroll down → Should see "deleted_at" column
└─ Repeat untuk "members" & "transaksi_parkirs"
```

---

## Tutorial 2: Understand How Soft Delete Works

### Duration: 5 minutes

```
CONCEPT:
┌─────────────────────────────────────────────┐
│ Soft Delete = Logical Delete                │
│ (tidak benar-benar dihapus)                  │
└─────────────────────────────────────────────┘

EXAMPLE:

1. DATABASE BEFORE DELETE:
   ┌────┬──────────┬───────────┐
   │ id │ username │ deleted_at│
   ├────┼──────────┼───────────┤
   │  1 │ john     │ NULL      │
   │  2 │ jane     │ NULL      │
   │  3 │ bob      │ NULL      │
   └────┴──────────┴───────────┘

2. SOFT DELETE user with id=3:
   UPDATE users SET deleted_at = '2024-02-04 10:30:00' WHERE id = 3

3. DATABASE AFTER SOFT DELETE:
   ┌────┬──────────┬────────────────────────┐
   │ id │ username │ deleted_at             │
   ├────┼──────────┼────────────────────────┤
   │  1 │ john     │ NULL                   │
   │  2 │ jane     │ NULL                   │
   │  3 │ bob      │ 2024-02-04 10:30:00   │  ← Masih ada di DB!
   └────┴──────────┴────────────────────────┘

4. NORMAL QUERY (exclude soft delete):
   SELECT * FROM users WHERE deleted_at IS NULL
   
   Result:
   ┌────┬──────────┐
   │ id │ username │
   ├────┼──────────┤
   │  1 │ john     │
   │  2 │ jane     │
   └────┴──────────┘
   
   Bob tidak muncul! ✓

5. ADMIN QUERY (include soft delete):
   SELECT * FROM users
   
   Result:
   ┌────┬──────────┐
   │ id │ username │
   ├────┼──────────┤
   │  1 │ john     │
   │  2 │ jane     │
   │  3 │ bob      │  ← Admin bisa lihat
   └────┴──────────┘

6. RESTORE:
   UPDATE users SET deleted_at = NULL WHERE id = 3
   
   Result:
   Bob muncul di normal query lagi ✓

7. HARD DELETE (permanent):
   DELETE FROM users WHERE id = 3
   
   Bob benar-benar hilang dari database
```

---

## Tutorial 3: Using Models

### Duration: 5 minutes

```
SCENARIO: You want to get active users

OLD WAY (without soft delete):
$db = Database::connect();
$users = $db->table('users')->get()->getResult();
// Gets semua users, termasuk yang sudah dihapus ❌

NEW WAY (with soft delete):
$userModel = new UserModel();
$users = $userModel->findAll();
// Otomatis exclude soft delete ✓

HOW IT WORKS INTERNALLY:
├─ UserModel extends BaseModel
├─ BaseModel has $useSoftDeletes = true
├─ findAll() otomatis add WHERE deleted_at IS NULL
└─ Magic! ✨

DIFFERENT SCENARIOS:

1. Get active user:
   $user = $userModel->find(1);
   Query: SELECT * FROM users WHERE id=1 AND deleted_at IS NULL
   
2. Get all active users:
   $users = $userModel->findAll();
   Query: SELECT * FROM users WHERE deleted_at IS NULL
   
3. Get user (including deleted):
   $user = $userModel->withDeleted()->find(1);
   Query: SELECT * FROM users WHERE id=1
   
4. Get only deleted users:
   $users = $userModel->onlyDeleted()->findAll();
   Query: SELECT * FROM users WHERE deleted_at IS NOT NULL
   
5. Get with condition:
   $users = $userModel->where('role_id', 2)->findAll();
   Query: SELECT * FROM users WHERE role_id=2 AND deleted_at IS NULL
   
6. Get with condition (include deleted):
   $users = $userModel->withDeleted()
                      ->where('role_id', 2)
                      ->findAll();
   Query: SELECT * FROM users WHERE role_id=2
```

---

## Tutorial 4: Login System (Most Important!)

### Duration: 5 minutes

```
SCENARIO: User tries to login

OLD CODE (without soft delete):
public function attemptLogin()
{
    $db = Database::connect();
    $user = $db->table('users')
               ->where('username', $username)
               ->first();
    
    if (!$user) {
        return redirect()->with('error', 'User not found');
    }
    // Problem: Could find deleted user! ❌
}

NEW CODE (with soft delete):
public function attemptLogin()
{
    $userModel = new UserModel();
    $user = $userModel->where('username', $username)->first();
    
    if (!$user) {
        return redirect()->with('error', 'Username not found or account deleted');
    }
    // Deleted user won't be found! ✓
}

HOW IT WORKS:
Step 1: User submits login form
        username: "bob"
        password: "123456"

Step 2: Code queries: $userModel->where('username', 'bob')->first()
        
Step 3: BaseModel automatically adds: WHERE deleted_at IS NULL
        
Step 4: Final query:
        SELECT * FROM users
        WHERE username = 'bob' AND deleted_at IS NULL
        
Step 5a: If user not deleted:
         Query finds user ✓
         Password check succeeds ✓
         User logged in ✓
         
Step 5b: If user was deleted (deleted_at = '2024-02-04 10:30:00'):
         Query doesn't find user (because deleted_at != NULL)
         Returns NULL ❌
         Login fails ❌
         User can't login! ✓✓✓

RESULT: Deleted users CANNOT login
```

---

## Tutorial 5: Admin Trash Management

### Duration: 5 minutes

```
SCENARIO: Admin wants to manage deleted users

FLOW:
┌─────────────────────────────────────┐
│ Admin Dashboard                      │
├─────────────────────────────────────┤
│ ✓ Active Users (100)                │
│ 🗑️ Trash (5 deleted users)          │
│ ⚙️ Settings                         │
└─────────────────────────────────────┘

FEATURE 1: View Active Users
$userModel = new UserModel();
$activeUsers = $userModel->findAll();
Query: SELECT * FROM users WHERE deleted_at IS NULL
Display: 100 active users

FEATURE 2: View Deleted Users
$userModel = new UserModel();
$deletedUsers = $userModel->findOnlyDeleted();
Query: SELECT * FROM users WHERE deleted_at IS NOT NULL
Display: 5 deleted users

FEATURE 3: Restore Deleted User
Admin clicks "Restore" button on user "bob"
$userModel->restore(1);  // where id = 1
Query: UPDATE users SET deleted_at = NULL WHERE id = 1
Result: "bob" appears in active list again! ✓

FEATURE 4: Permanent Delete
Admin clicks "Delete Forever" with confirmation
$userModel->forceDelete(1);
Query: DELETE FROM users WHERE id = 1
Result: "bob" completely gone from database

FEATURE 5: View Statistics
$total = count($userModel->findAllWithDeleted());  // 105
$active = count($userModel->findAll());            // 100
$deleted = count($userModel->findOnlyDeleted());   // 5
```

---

## Tutorial 6: Implementation Checklist

### Duration: 10 minutes

```
CHECKLIST:

□ STEP 1: Database
  □ Run SOFT_DELETE_SETUP.sql
  □ Verify columns added to all tables

□ STEP 2: Models
  □ Create BaseModel.php (already done ✓)
  □ Update UserModel extends BaseModel
  □ Update MemberModel extends BaseModel
  □ Update TransaksiParkirModel extends BaseModel
  □ Add 'deleted_at' to allowedFields

□ STEP 3: Auth
  □ Update Auth::attemptLogin() to use UserModel
  □ Verify query uses model (not raw DB)
  □ Test login with deleted user (should fail)

□ STEP 4: Helper
  □ Create SoftDeleteHelper.php (already done ✓)
  □ Test helper functions in controller

□ STEP 5: Controllers
  □ Copy ManageUsers.php to app/Controllers/Admin/
  □ Update other controllers to use soft delete

□ STEP 6: Routes
  □ Add routes for soft delete operations
  □ Add routes for trash/restore

□ STEP 7: Views
  □ Create trash view to show deleted items
  □ Add restore button
  □ Add hard delete button with confirmation

□ STEP 8: Test
  □ Create user
  □ Soft delete user
  □ Verify user not in active list
  □ Verify user can't login
  □ Restore user
  □ Verify user can login again
  □ Hard delete user
  □ Verify user completely gone
```

---

## Tutorial 7: Common Mistakes to Avoid

### Duration: 5 minutes

```
MISTAKE 1: Using raw query instead of model
❌ WRONG:
   $db = Database::connect();
   $user = $db->table('users')->where('username', $u)->first();
   
✅ RIGHT:
   $userModel = new UserModel();
   $user = $userModel->where('username', $u)->first();

MISTAKE 2: Forget to extend BaseModel
❌ WRONG:
   class UserModel extends Model { }
   
✅ RIGHT:
   class UserModel extends BaseModel { }

MISTAKE 3: Forget to add deleted_at to allowedFields
❌ WRONG:
   protected $allowedFields = ['username', 'email'];
   
✅ RIGHT:
   protected $allowedFields = ['username', 'email', 'deleted_at'];

MISTAKE 4: Using DELETE instead of soft delete
❌ WRONG:
   DELETE FROM users WHERE id = 1;  (permanently gone)
   
✅ RIGHT:
   UPDATE users SET deleted_at = NOW() WHERE id = 1;  (soft delete)

MISTAKE 5: Forget to check soft delete in login
❌ WRONG:
   if (!$user) return login_failed;
   // Works but doesn't distinguish "not found" vs "deleted"
   
✅ RIGHT:
   if (!$user) return redirect()->with('error', 'Username not found or account deleted');

MISTAKE 6: Join query without soft delete check
❌ WRONG:
   $user = $userModel->select('users.*, roles.nama_role')
                    ->join('roles', 'roles.id = users.role_id')
                    ->where('username', $u)
                    ->first();
   // Works if only users table is soft delete
   
✅ RIGHT:
   // Same code (if only users soft delete)
   // But if roles also soft delete:
   $user = $userModel->select('users.*, roles.nama_role')
                    ->join('roles', 'roles.id = users.role_id')
                    ->where('roles.deleted_at IS NULL')  // Add this
                    ->where('username', $u)
                    ->first();
```

---

## Tutorial 8: Testing Your Implementation

### Duration: 5 minutes

```
TEST 1: Soft Delete Works
CODE:
$userModel = new UserModel();
$userId = $userModel->insert(['username' => 'testuser']);

// Soft delete
$userModel->delete($userId);

// Check: User should not be in active list
$user = $userModel->find($userId);
assert($user === null, "User should not be found");  ✓

// Check: User should be in deleted list
$user = $userModel->findByIdWithDeleted($userId);
assert($user !== null, "User should be found with deleted");  ✓
assert($user['deleted_at'] !== null, "deleted_at should be set");  ✓

TEST 2: Login Fails for Deleted User
CODE:
// Create and delete user
$userModel->insert(['username' => 'bob', 'password' => 'hash123']);
$userModel->delete(1);

// Try to login
$user = $userModel->where('username', 'bob')->first();
assert($user === null, "Login should fail");  ✓

TEST 3: Restore Works
CODE:
$userModel->restore(1);

// Check: User should be found again
$user = $userModel->find(1);
assert($user !== null, "User should be found after restore");  ✓
assert($user['deleted_at'] === null, "deleted_at should be null");  ✓

// Check: User can login again
$user = $userModel->where('username', 'bob')->first();
assert($user !== null, "Login should work after restore");  ✓

TEST 4: Hard Delete Works
CODE:
$userModel->forceDelete(1);

// Check: User should be completely gone
$user = $userModel->findByIdWithDeleted(1);
assert($user === null, "User should be completely deleted");  ✓
```

---

## Tutorial 9: Real World Example - Complete Flow

### Duration: 10 minutes

```
SCENARIO: Admin bans a user

FLOW:

1. ADMIN CLICKS "BAN USER"
   └─ Goes to /admin/users/delete/5
   
2. CONTROLLER EXECUTES:
   public function delete($id) {
       $this->userModel->delete($id);
       return redirect()->with('success', 'User banned');
   }
   
3. DATABASE UPDATES:
   UPDATE users SET deleted_at = '2024-02-04 10:30:00' WHERE id = 5
   
4. NEXT DAY - USER TRIES TO LOGIN:
   public function attemptLogin() {
       $user = $userModel->where('username', 'banned_user')->first();
       
       if (!$user) {
           return redirect()->with('error', 'Account deleted or not found');
       }
   }
   
   Query: SELECT * FROM users 
          WHERE username = 'banned_user' AND deleted_at IS NULL
   
   Result: NULL (user not found because deleted_at is set)
   User cannot login ✓

5. ADMIN GOES TO TRASH:
   public function trash() {
       $deleted = $userModel->findOnlyDeleted();
       return view('admin/trash', ['users' => $deleted]);
   }
   
   Shows: "banned_user" in trash list

6. ADMIN DECIDES TO UNBAN:
   public function restore($id) {
       $userModel->restore($id);
       return redirect()->with('success', 'User unbanned');
   }
   
   Database updates:
   UPDATE users SET deleted_at = NULL WHERE id = 5
   
   User can login again ✓

7. OR ADMIN DECIDES TO PERMANENTLY DELETE:
   public function destroy($id) {
       $userModel->forceDelete($id);
       return redirect()->with('success', 'User permanently deleted');
   }
   
   Database deletes:
   DELETE FROM users WHERE id = 5
   
   User completely gone ✓
```

---

## Tutorial 10: Summary & Best Practices

### Duration: 5 minutes

```
SUMMARY OF SOFT DELETE:

What is it?
├─ Marking data as deleted without removing it
├─ Set deleted_at timestamp
└─ Exclude from normal queries automatically

Why use it?
├─ Data safety (no accidental loss)
├─ Easy recovery
├─ Business logic (users can't login if deleted)
├─ Audit trail (when was it deleted)
└─ Compliance

How to implement?
├─ 1. Add deleted_at column
├─ 2. Extend BaseModel
├─ 3. Update queries to use model
└─ 4. Create admin interface for restore

Best Practices:
├─ Always use model instead of raw query
├─ Extend BaseModel for all soft-deletable models
├─ Create trash views for admin
├─ Log who deleted and when
├─ Require confirmation for hard delete
├─ Backup database regularly
└─ Document soft delete behavior

Key Points to Remember:
├─ Soft delete != hard delete
├─ Always check soft delete in login/auth
├─ Models automatically exclude soft delete
├─ Use withDeleted() to include deleted
├─ Use forceDelete() for permanent delete
└─ Deleted data still counts toward storage

Testing:
├─ Test soft delete works
├─ Test deleted users can't login
├─ Test restore works
├─ Test hard delete works
└─ Test queries exclude soft delete

Common Mistakes:
├─ Using raw query instead of model ❌
├─ Forgetting to extend BaseModel ❌
├─ Not checking soft delete in auth ❌
├─ Using DELETE instead of UPDATE ❌
└─ Not creating trash interface ❌
```

---

**Tutorial Complete!** 🎉

You should now understand:
✓ What soft delete is
✓ How it works
✓ How to implement it
✓ How to use it in your application
✓ Common mistakes to avoid
✓ How to test it

Next steps:
1. Run SOFT_DELETE_SETUP.sql
2. Verify models are updated
3. Copy admin controllers
4. Create views
5. Test everything

Good luck! 🚀
