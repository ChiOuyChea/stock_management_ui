// 🔧 Change all fetch URLs from /api/products to /api/product

// CREATE
await fetch('http://localhost:3000/api/product', {  // ← singular
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
});

// READ ALL (for refresh)
await fetch('http://localhost:3000/api/product');  // ← singular

// UPDATE
await fetch(`http://localhost:3000/api/product/${id}`, {  // ← singular
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
});

// DELETE
await fetch(`http://localhost:3000/api/product/${id}`, {  // ← singular
    method: 'DELETE'
});