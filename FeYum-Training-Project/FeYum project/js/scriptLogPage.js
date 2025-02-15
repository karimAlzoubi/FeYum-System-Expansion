// script.js
document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    
    if (username === 'demo@example.com' && password === 'demo123') {
      alert('تم تسجيل الدخول بنجاح!');
    } else {
      alert('اسم المستخدم أو كلمة المرور غير صحيحة.');
    }
  });
  