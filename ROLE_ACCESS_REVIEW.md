# Role-Based Access Control Review

## Executive Summary
After reviewing the role-based access control implementation, I've identified **7 critical security issues** and **3 usability concerns** that need immediate attention.

---

## 🚨 Critical Security Issues

### 1. **Officers Can View Registration Details for ANY Event**
**Location:** `RegistrationController@show` (line 69-73)

**Problem:**
```php
public function show(EventRegistration $registration)
{
    $registration->load('event', 'user', 'attendanceLogs');
    return view('registrations.show', compact('registration'));
}
```
- No authorization check
- Officers can access `/registrations/{id}` for ANY registration, even from other clubs
- Students can also view other students' registration details

**Impact:** Privacy breach - officers can see personal information of students registered for other clubs' events

**Fix Required:**
```php
public function show(EventRegistration $registration)
{
    $user = auth()->user();
    
    // Authorization check
    if ($user->role === 'officer' && $user->club_id) {
        if ($registration->event->club_id !== $user->club_id) {
            abort(403, 'You can only view registrations for your club\'s events.');
        }
    } elseif ($user->role === 'student') {
        if ($registration->user_id !== $user->id) {
            abort(403, 'You can only view your own registrations.');
        }
    }
    
    $registration->load('event', 'user', 'attendanceLogs');
    return view('registrations.show', compact('registration'));
}
```

---

### 2. **Officers Can View Attendance Logs for ANY Event**
**Location:** `AttendanceController@show` (line 54-57)

**Problem:**
```php
public function show(AttendanceLog $attendance)
{
    $attendance->load('registration.event', 'registration.user');
    return view('attendance.show', compact('attendance'));
}
```
- No authorization check
- Officers can access `/attendance/{id}` for ANY attendance log

**Impact:** Officers can track student attendance across all clubs

**Fix Required:**
```php
public function show(AttendanceLog $attendance)
{
    $user = auth()->user();
    
    // Authorization check
    if ($user->role === 'officer' && $user->club_id) {
        if ($attendance->registration->event->club_id !== $user->club_id) {
            abort(403, 'You can only view attendance for your club\'s events.');
        }
    } elseif ($user->role === 'student') {
        if ($attendance->registration->user_id !== $user->id) {
            abort(403, 'You can only view your own attendance.');
        }
    }
    
    $attendance->load('registration.event', 'registration.user');
    return view('attendance.show', compact('attendance'));
}
```

---

### 3. **Officers Can View Event Details for ANY Club**
**Location:** `EventController@show` (line 70-74)

**Problem:**
```php
public function show(Event $event)
{
    $event->load('club', 'registrations.user');
    return view('events.show', compact('event'));
}
```
- No authorization check for officers
- Officers can see detailed information about other clubs' events
- Officers can see the full list of registered users for other clubs' events

**Impact:** Information leakage - officers can spy on competitor clubs' events and attendees

**Fix Required:**
```php
public function show(Event $event)
{
    $user = auth()->user();
    
    // Officers should only view their club's events
    if ($user->role === 'officer' && $user->club_id) {
        if ($event->club_id !== $user->club_id) {
            abort(403, 'You can only view your club\'s events.');
        }
    }
    
    $event->load('club', 'registrations.user');
    return view('events.show', compact('event'));
}
```

---

### 4. **Students Can Create Registrations for OTHER Users**
**Location:** `RegistrationController@store` (line 43-67)

**Problem:**
```php
public function store(Request $request)
{
    $request->validate([
        'event_id' => 'required|exists:events,id',
        'user_id' => 'required|exists:users,id', // ⚠️ Student can specify any user_id
        'status' => 'required|in:registered,attended',
    ]);
    
    EventRegistration::create([...]);
}
```
- Students can register OTHER students for events
- No validation that `user_id` matches the authenticated user

**Impact:** Students can fraudulently register others, causing capacity issues and data integrity problems

**Fix Required:**
```php
public function store(Request $request)
{
    $user = auth()->user();
    
    $request->validate([
        'event_id' => 'required|exists:events,id',
        'user_id' => 'required|exists:users,id',
        'status' => 'required|in:registered,attended',
    ]);
    
    // Students can only register themselves
    if ($user->role === 'student' && $request->user_id != $user->id) {
        abort(403, 'You can only register yourself for events.');
    }
    
    // Check if already registered
    $existing = EventRegistration::where('event_id', $request->event_id)
        ->where('user_id', $request->user_id)
        ->first();
    if ($existing) {
        return back()->withErrors(['user_id' => 'User is already registered for this event.']);
    }
    
    EventRegistration::create([
        'event_id' => $request->event_id,
        'user_id' => $request->user_id,
        'ticket_code' => Str::random(10),
        'status' => $request->status,
    ]);
    
    return redirect()->route('registrations.index')->with('success', 'Registration created successfully.');
}
```

---

### 5. **Officers Can Mark Attendance for ANY Club's Events**
**Location:** `AttendanceController@create` and `store` (lines 36-51)

**Problem:**
```php
public function create()
{
    $registrations = EventRegistration::with('event', 'user')->get(); // ⚠️ All registrations
    return view('attendance.create', compact('registrations'));
}

public function store(Request $request)
{
    $request->validate([
        'registration_id' => 'required|exists:event_registrations,id',
        'timestamp' => 'required|date',
    ]);
    
    AttendanceLog::create($request->all()); // ⚠️ No authorization check
}
```
- Officers see ALL registrations when marking attendance
- Officers can mark attendance for other clubs' events

**Impact:** Data integrity issue - officers can manipulate attendance records for other clubs

**Fix Required:**
```php
public function create()
{
    $user = auth()->user();
    
    if ($user->role === 'admin') {
        $registrations = EventRegistration::with('event', 'user')->get();
    } elseif ($user->role === 'officer' && $user->club_id) {
        // Officers can only see registrations for their club's events
        $registrations = EventRegistration::with('event', 'user')
            ->whereHas('event', function($query) use ($user) {
                $query->where('club_id', $user->club_id);
            })
            ->get();
    } else {
        abort(403, 'Unauthorized access.');
    }
    
    return view('attendance.create', compact('registrations'));
}

public function store(Request $request)
{
    $user = auth()->user();
    
    $request->validate([
        'registration_id' => 'required|exists:event_registrations,id',
        'timestamp' => 'required|date',
    ]);
    
    // Verify officer can only mark attendance for their club's events
    if ($user->role === 'officer' && $user->club_id) {
        $registration = EventRegistration::with('event')->findOrFail($request->registration_id);
        if ($registration->event->club_id !== $user->club_id) {
            abort(403, 'You can only mark attendance for your club\'s events.');
        }
    }
    
    AttendanceLog::create($request->all());
    
    return redirect()->route('attendance.index')->with('success', 'Attendance log created successfully.');
}
```

---

### 6. **Officers Can Edit/Delete Attendance Logs for ANY Event**
**Location:** `AttendanceController@edit`, `update`, `destroy` (lines 60-82)

**Problem:**
- No authorization checks in edit, update, or destroy methods
- Officers can modify/delete attendance records for other clubs

**Impact:** Data integrity and audit trail issues

**Fix Required:** Add authorization checks similar to the store method above.

---

### 7. **Missing Club Assignment Validation**
**Location:** Multiple controllers

**Problem:**
- Officers without `club_id` can bypass many checks
- No validation that officer's `club_id` is not null before allowing access

**Impact:** Officers not assigned to clubs can potentially access all data

**Fix Required:** Add middleware or controller-level check:
```php
// In each controller method for officers
if ($user->role === 'officer' && !$user->club_id) {
    abort(403, 'You must be assigned to a club to access this resource.');
}
```

---

## ⚠️ Usability Issues

### 1. **Students See "Create Registration" But Should Use "Register for Event"**
**Location:** Routes line 47-48

**Problem:**
- The route name suggests students are "creating" registrations
- UX should be "Register for Event" not "Create Registration"
- Students shouldn't see a form with user_id dropdown

**Recommendation:** Create separate student registration flow

---

### 2. **Registration Create Form Shows All Users to Students**
**Location:** `RegistrationController@create` (line 36-41)

**Problem:**
```php
public function create()
{
    $events = Event::all();
    $users = User::all(); // ⚠️ Students see all users
    return view('registrations.create', compact('events', 'users'));
}
```

**Fix Required:**
```php
public function create()
{
    $user = auth()->user();
    $events = Event::all();
    
    if ($user->role === 'admin') {
        $users = User::all();
    } else {
        // Students only see themselves
        $users = User::where('id', $user->id)->get();
    }
    
    return view('registrations.create', compact('events', 'users'));
}
```

---

### 3. **No Capacity Validation When Registering**
**Location:** `RegistrationController@store`

**Problem:**
- No check if event is at capacity
- Students can register for full events

**Fix Required:**
```php
// In store method, before creating registration
$event = Event::findOrFail($request->event_id);
$currentRegistrations = EventRegistration::where('event_id', $event->id)->count();

if ($currentRegistrations >= $event->capacity) {
    return back()->withErrors(['event_id' => 'This event is at full capacity.']);
}
```

---

## Summary of Required Changes

### High Priority (Security)
1. ✅ Add authorization to `RegistrationController@show`
2. ✅ Add authorization to `AttendanceController@show`
3. ✅ Add authorization to `EventController@show` for officers
4. ✅ Prevent students from registering other users
5. ✅ Filter registrations in `AttendanceController@create` by club
6. ✅ Add authorization to `AttendanceController@store`
7. ✅ Add authorization to `AttendanceController@edit/update/destroy`
8. ✅ Validate officer has club_id before allowing access

### Medium Priority (Usability)
1. ✅ Filter users in `RegistrationController@create` based on role
2. ✅ Add event capacity validation
3. ✅ Improve student registration UX

### Low Priority (Enhancement)
1. Consider adding audit logging for sensitive operations
2. Add rate limiting for registration endpoints
3. Consider soft deletes for better audit trail

---

## Testing Checklist

After implementing fixes, test the following scenarios:

### Officer Tests
- [ ] Officer cannot view events from other clubs
- [ ] Officer cannot view registrations from other clubs' events
- [ ] Officer cannot view attendance logs from other clubs' events
- [ ] Officer cannot mark attendance for other clubs' events
- [ ] Officer without club_id is denied access

### Student Tests
- [ ] Student cannot register other students
- [ ] Student can only view their own registrations
- [ ] Student can only view their own attendance
- [ ] Student cannot register for full events

### Admin Tests
- [ ] Admin can access all resources
- [ ] Admin can manage all clubs' data

---

## Conclusion

The current implementation has **significant security vulnerabilities** that allow:
1. Officers to access data from other clubs
2. Students to register other users
3. Unauthorized viewing of sensitive information

These issues should be addressed **immediately** before the system goes into production.