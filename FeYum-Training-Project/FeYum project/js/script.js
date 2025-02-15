//comunication

document.addEventListener("DOMContentLoaded", () => {
    // Update all percentage elements
    const updatePercentages = () => {
        document.querySelectorAll('.percentage').forEach(element => {
            const matchValue = parseFloat(element.textContent);
            element.style.backgroundColor = matchValue > 50 ? '#28a745' : 
                                         matchValue > 30 ? '#ffc107' : '#dc3545';
        });
    };

    // Dropdown functionality
    document.querySelectorAll('.dropdown-content a').forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            document.querySelectorAll('.dropdown-content a').forEach(link => 
                link.classList.remove('active'));
            item.classList.add('active');
            window.location.href = item.getAttribute('href');
        });
    });

    // Initialize
    updatePercentages();
});



document.addEventListener("DOMContentLoaded", () => {
    // تفعيل القائمة المنسدلة وتغيير اللون عند النقر
    document.querySelectorAll('.dropdown-content a').forEach((item) => {
        item.addEventListener('click', () => {
            // إزالة الكلاس "active" من جميع العناصر
            document.querySelectorAll('.dropdown-content a').forEach((link) => {
                link.classList.remove('active');
            });

            // إضافة الكلاس "active" للعنصر الذي تم النقر عليه
            item.classList.add('active');
        });
    });

    // تبديل الأيقونة وزر التفاصيل في الرسائل
    const icons = document.querySelectorAll(".message-card .icon");

    icons.forEach((icon) => {
        icon.addEventListener("click", () => {
            // تبديل عرض الأيقونة وزر التفاصيل
            const detailsBtn = icon.nextElementSibling;
            if (detailsBtn.style.display === "block") {
                detailsBtn.style.display = "none";
                icon.style.display = "block";
            } else {
                detailsBtn.style.display = "block";
                icon.style.display = "none";
            }
        });
    });
});


/* ----------------------------------------------------------------------------------------------------------------------- */
/* Employee Dashboard changes */

// التفاعل مع الأزرار في الشريط الجانبي
document.querySelectorAll('.menu-item').forEach((item) => {
    item.addEventListener('click', () => {
        document.querySelectorAll('.menu-item').forEach((li) => li.classList.remove('active'));
        item.classList.add('active');
        alert(`تم الانتقال إلى صفحة: ${item.textContent}`);
    });
});

// عند الضغط على زر التفاصيل
document.querySelectorAll('.details-btn').forEach(button => {
    button.addEventListener('click', () => {
        alert('عرض تفاصيل الموظف');
    });
});
/* ----------------------------------------------------------------------------------------------------------------------- */
/* FirstPage changes */

function handleClick(event, action) {
    // تغيير النص داخل البطاقة بعد الضغط
    const card = event.target.closest('.card');
    const h3 = card.querySelector('h3');
    h3.innerText = `تم اختيار ${action}`;
    
    // تغيير لون الزر بعد الضغط
    event.target.style.backgroundColor = 'green';
    event.target.innerText = 'تم!';
    
    // عرض رسالة منبثقة
    alert(`تم اختيار: ${action}`);
}

// إضافة تأثير عند مرور الفأرة على البطاقات
const cards = document.querySelectorAll('.card');
cards.forEach(card => {
    card.addEventListener('mouseover', () => {
        card.style.transform = 'scale(1.05)'; // تكبير البطاقة عند التمرير
        card.style.boxShadow = '0 8px 12px rgba(0, 0, 0, 0.2)';
    });

    card.addEventListener('mouseout', () => {
        card.style.transform = 'scale(1)'; // إعادة الحجم الطبيعي
        card.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
    });
});

function showPopup(action) {
    const popup = document.createElement('div');
    popup.classList.add('popup');
    popup.innerHTML = `
        <div class="popup-content">
            <h2>${action}</h2>
            <p>تم اختيار هذا الإجراء بنجاح. شكراً لك!</p>
            <button onclick="closePopup()">إغلاق</button>
        </div>
    `;
    document.body.appendChild(popup);
}

function closePopup() {
    const popup = document.querySelector('.popup');
    popup.remove();
}/* ----------------------------------------------------------------------------------------------------------------------- */
/*ID and Name */

// تنفيذ الأزرار
document.getElementById("track-btn").addEventListener("click", () => {
    const orderNumber = document.getElementById("order-number").value;

    if (orderNumber.trim() === "") {
        alert("يرجى إدخال رقم الطلب.");
    } else {
        // يمكنك تعديل الإجراء هنا ليتم التحقق من الطلب
        alert(`تم تتبع الطلب رقم: ${orderNumber}`);
    }
});

document.getElementById("back-btn").addEventListener("click", () => {
    // يمكنك تعديل الإجراء للرجوع إلى الصفحة السابقة
    alert("العودة إلى الصفحة السابقة.");
});
/* ----------------------------------------------------------------------------------------------------------------------- */
/*communication with hr */



document.getElementById('loginForm').addEventListener('submit', function(e) {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    
    if (!email || !password) {
        e.preventDefault();
        const errorDiv = document.querySelector('.error-message');
        if (!errorDiv) {
            const newErrorDiv = document.createElement('div');
            newErrorDiv.className = 'error-message';
            newErrorDiv.textContent = 'الرجاء إدخال البريد الإلكتروني وكلمة المرور';
            this.insertBefore(newErrorDiv, this.firstChild);
        } else {
            errorDiv.style.display = 'block';
            errorDiv.textContent = 'الرجاء إدخال البريد الإلكتروني وكلمة المرور';
        }
    }
});
