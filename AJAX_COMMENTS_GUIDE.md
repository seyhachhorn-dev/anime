# AJAX Comments System - Simple Guide

## Overview
Comments now use AJAX (Asynchronous JavaScript and XML) technology to submit without reloading the page. When a user submits a comment, it's sent to the server in the background and the new comment appears instantly.

## How It Works - Step by Step

### 1. **User Submits Comment** (Frontend)
- User types comment in textarea
- Clicks "Send" button
- JavaScript intercepts the form submission (prevents page reload)

### 2. **AJAX Request** (JavaScript → Server)
- Comment data is sent to `add-comment-ajax.php` via AJAX
- Server receives: comment text, show_id, user info
- No page reload happens

### 3. **Backend Processing** (PHP)
- `add-comment-ajax.php` receives the AJAX request
- Checks if user is logged in (401 error if not)
- Validates comment is not empty (400 error if empty)
- `insertComment()` function saves to database
- Returns JSON response with success status

### 4. **Response Handling** (JavaScript)
- If successful ✅
  - New comment appears at top of list instantly
  - Textarea clears
  - Success SweetAlert message shown
  
- If failed ❌
  - Error SweetAlert message shown
  - User can retry

## Files Modified

### 1. **add-comment-ajax.php** (NEW)
Handles AJAX comment submissions
```php
- Validates user login
- Validates comment text
- Saves to database
- Returns JSON response
```

### 2. **anime-details.php** (UPDATED)
- Added `<div id="comments-container">` wrapper for comment list
- Updated form with id="comment-form"
- Added JavaScript AJAX handler
- Removed old page-reload logic

### 3. **anime-watching.php** (UPDATED)
- Added `<div id="comments-container-watching">` wrapper
- Updated form with id="comment-form-watching"
- Added JavaScript AJAX handler
- Removed old page-reload logic

## JavaScript Logic (Simplified)

```javascript
// 1. Catch form submission
$('#comment-form').submit(function(e) {
    e.preventDefault(); // Stop page reload
    
    // 2. Get comment text
    var commentText = $('#comment-input').val();
    
    // 3. Send AJAX request
    $.ajax({
        type: 'POST',
        url: 'add-comment-ajax.php',
        data: { comment: commentText, show_id: showId },
        dataType: 'json',
        
        // 4. If successful
        success: function(response) {
            // Add new comment to list
            $('#comments-container').prepend(newCommentHTML);
            // Clear textarea
            $('#comment-input').val('');
            // Show success message
        }
    });
});
```

## Database
No database schema changes needed. Uses existing `comments` table:
- comment
- show_id
- user_id
- user_name
- created_at

## Error Handling

| Error | Cause | UI Feedback |
|-------|-------|------------|
| 401 | Not logged in | Error alert + redirect to login |
| 400 | Empty comment | Error alert |
| 500 | Database error | Error alert |

## Benefits

✅ **No Page Reload** - User stays in same position  
✅ **Instant Feedback** - Comments appear immediately  
✅ **Better UX** - Smooth, modern feel  
✅ **Same Data** - Still saves to database normally  
✅ **Error Handling** - Professional error messages with SweetAlert

## Testing

1. Click comment button - form submits without reload
2. New comment appears at top instantly
3. Try commenting without text - error message
4. Try commenting while logged out - redirects to login
5. Refresh page - comment still there (saved in DB)
